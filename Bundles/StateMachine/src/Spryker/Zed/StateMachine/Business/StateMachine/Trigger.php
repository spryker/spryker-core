<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Exception;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Business\Exception\CommandNotFoundException;
use Spryker\Zed\StateMachine\Business\Exception\TriggerException;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

class Trigger implements TriggerInterface
{
    public const MAX_EVENT_REPEATS = 10;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface
     */
    protected $transitionLog;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface
     */
    protected $stateMachineHandlerResolver;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface
     */
    protected $stateMachinePersistence;

    /**
     * @var array
     */
    protected $eventCounter = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface
     */
    protected $condition;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface
     */
    protected $stateUpdater;

    /**
     * @var int
     */
    protected $affectedItems = 0;

    /**
     * @param \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface $transitionLog
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface $stateMachineHandlerResolver
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface $finder
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface $stateMachinePersistence
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface $condition
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface $stateUpdater
     */
    public function __construct(
        TransitionLogInterface $transitionLog,
        HandlerResolverInterface $stateMachineHandlerResolver,
        FinderInterface $finder,
        PersistenceInterface $stateMachinePersistence,
        ConditionInterface $condition,
        StateUpdaterInterface $stateUpdater
    ) {
        $this->transitionLog = $transitionLog;
        $this->stateMachineHandlerResolver = $stateMachineHandlerResolver;
        $this->finder = $finder;
        $this->stateMachinePersistence = $stateMachinePersistence;
        $this->condition = $condition;
        $this->stateUpdater = $stateUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @return int
     */
    public function triggerForNewStateMachineItem(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    ) {
        $stateMachineProcessTransfer->requireStateMachineName()
            ->requireProcessName();

        $stateMachineItemTransfer = $this->createItemTransferForNewProcess($stateMachineProcessTransfer, $identifier);

        $processes = $this->finder->findProcessesForItems([$stateMachineItemTransfer]);

        $itemsWithOnEnterEvent = $this->finder->filterItemsWithOnEnterEvent([$stateMachineItemTransfer], $processes);

        $this->triggerOnEnterEvents($itemsWithOnEnterEvent);

        return $this->affectedItems;
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return int
     */
    public function triggerEvent($eventName, array $stateMachineItems)
    {
        if ($this->checkForEventRepetitions($eventName) === false) {
            return 0;
        }

        $stateMachineItems = $this->stateMachinePersistence
            ->updateStateMachineItemsFromPersistence($stateMachineItems);

        $processes = $this->finder->findProcessesForItems($stateMachineItems);
        $stateMachineItems = $this->filterEventAffectedItems($eventName, $stateMachineItems, $processes);

        $this->transitionLog->init($stateMachineItems);
        $this->logSourceState($stateMachineItems);

        $this->runCommand($eventName, $stateMachineItems, $processes);

        $sourceStateBuffer = $this->updateStateByEvent($eventName, $stateMachineItems);

        $this->stateUpdater->updateStateMachineItemState(
            $stateMachineItems,
            $processes,
            $sourceStateBuffer
        );

        $stateMachineItemsWithOnEnterEvent = $this->finder->filterItemsWithOnEnterEvent(
            $stateMachineItems,
            $processes,
            $sourceStateBuffer
        );

        $this->transitionLog->saveAll();

        $this->affectedItems += count($stateMachineItems);

        $this->triggerOnEnterEvents($stateMachineItemsWithOnEnterEvent);

        return $this->affectedItems;
    }

    /**
     * @param string $stateMachineName
     *
     * @return int
     */
    public function triggerConditionsWithoutEvent($stateMachineName)
    {
        $stateMachineHandler = $this->stateMachineHandlerResolver->get($stateMachineName);
        foreach ($stateMachineHandler->getActiveProcesses() as $processName) {
            $stateMachineItemsWithOnEnterEvent = $this->condition->getOnEnterEventsForStatesWithoutTransition(
                $stateMachineName,
                $processName
            );
            $this->triggerOnEnterEvents($stateMachineItemsWithOnEnterEvent);
        }

        return $this->affectedItems;
    }

    /**
     * @param string $stateMachineName
     *
     * @return int
     */
    public function triggerForTimeoutExpiredItems($stateMachineName)
    {
        $stateMachineItems = $this->stateMachinePersistence->getItemsWithExpiredTimeouts($stateMachineName);

        $groupedStateMachineItems = $this->groupItemsByEvent($stateMachineItems);
        foreach ($groupedStateMachineItems as $event => $stateMachineItems) {
            $this->triggerEvent($event, $stateMachineItems);
        }

        return $this->affectedItems;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return array
     */
    protected function groupItemsByEvent(array $stateMachineItems)
    {
        $groupedStateMachineItems = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $eventName = $stateMachineItemTransfer->getEventName();
            if (!isset($groupedStateMachineItems[$eventName])) {
                $groupedStateMachineItems[$eventName] = [];
            }
            $groupedStateMachineItems[$eventName][] = $stateMachineItemTransfer;
        }

        return $groupedStateMachineItems;
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    protected function filterEventAffectedItems($eventName, array $stateMachineItems, $processes)
    {
        $stateMachineItemsFiltered = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->requireStateName()->getStateName();
            $processName = $stateMachineItemTransfer->requireProcessName()->getProcessName();
            if (!isset($processes[$processName])) {
                continue;
            }

            $process = $processes[$processName];
            $state = $process->getStateFromAllProcesses($stateName);
            if ($state->hasEvent($eventName)) {
                $stateMachineItemsFiltered[] = $stateMachineItemTransfer;
            }
        }

        return $stateMachineItemsFiltered;
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function runCommand($eventName, array $stateMachineItems, array $processes)
    {
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->requireStateName()->getStateName();
            $processName = $stateMachineItemTransfer->requireProcessName()->getProcessName();
            if (!isset($processes[$processName])) {
                continue;
            }

            $process = $processes[$processName];
            $state = $process->getStateFromAllProcesses($stateName);
            $event = $state->getEvent($eventName);

            if (!$event->hasCommand()) {
                continue;
            }

            $commandPlugin = $this->getCommand($event->getCommand(), $stateMachineItemTransfer->getStateMachineName());

            $this->transitionLog->addCommand($stateMachineItemTransfer, $commandPlugin);

            try {
                $commandPlugin->run($stateMachineItemTransfer);
            } catch (Exception $e) {
                $this->transitionLog->setIsError(true);
                $this->transitionLog->setErrorMessage(get_class($commandPlugin) . ' - ' . $e->getMessage());
                $this->transitionLog->saveAll();
                throw $e;
            }
        }
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return array
     */
    protected function updateStateByEvent($eventName, array $stateMachineItems)
    {
        $sourceStateBuffer = [];
        $targetStateMap = [];
        foreach ($stateMachineItems as $i => $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->requireStateName()->getStateName();
            $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()] = $stateName;

            $process = $this->finder->findProcessByStateMachineAndProcessName(
                $stateMachineItemTransfer->getStateMachineName(),
                $stateMachineItemTransfer->getProcessName()
            );

            $sourceState = $process->getStateFromAllProcesses($stateName);

            $event = $sourceState->getEvent($eventName);
            $this->transitionLog->setEvent($event);

            $this->transitionLog->addSourceState($stateMachineItemTransfer, $sourceState->getName());

            $targetState = $sourceState;
            if ($eventName && $sourceState->hasEvent($eventName)) {
                $transitions = $sourceState->getEvent($eventName)->getTransitionsBySource($sourceState);
                $targetState = $this->condition->getTargetStatesFromTransitions(
                    $transitions,
                    $stateMachineItemTransfer,
                    $sourceState,
                    $this->transitionLog
                );
                $this->transitionLog->addTargetState($stateMachineItemTransfer, $targetState->getName());
            }

            $targetStateMap[$i] = $targetState->getName();
        }

        foreach ($stateMachineItems as $i => $stateMachineItemTransfer) {
            $this->stateMachinePersistence->saveStateMachineItem($stateMachineItems[$i], $targetStateMap[$i]);
        }

        return $sourceStateBuffer;
    }

    /**
     * To protect of loops, every event can only be used some times
     *
     * @param string $eventName
     *
     * @return bool
     */
    protected function checkForEventRepetitions($eventName)
    {
        if (array_key_exists($eventName, $this->eventCounter) === false) {
            $this->eventCounter[$eventName] = 0;
        }
        $this->eventCounter[$eventName]++;

        return $this->eventCounter[$eventName] < self::MAX_EVENT_REPEATS;
    }

    /**
     * @param array $itemsWithOnEnterEvent Keys are event names, values are collections of StateMachineItem transfer objects
     *
     * @return bool
     */
    protected function triggerOnEnterEvents(array $itemsWithOnEnterEvent)
    {
        if (count($itemsWithOnEnterEvent) > 0) {
            foreach ($itemsWithOnEnterEvent as $eventName => $stateMachineItems) {
                $this->triggerEvent($eventName, $stateMachineItems);
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $commandString
     * @param string $stateMachineName
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface
     */
    protected function getCommand($commandString, $stateMachineName)
    {
        $stateMachineHandler = $this->stateMachineHandlerResolver->get($stateMachineName);

        $this->assertCommandIsSet($commandString, $stateMachineHandler);

        return $stateMachineHandler->getCommandPlugins()[$commandString];
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return void
     */
    protected function logSourceState(array $stateMachineItems)
    {
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->getStateName();
            $this->transitionLog->addSourceState($stateMachineItemTransfer, $stateName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function createItemTransferForNewProcess(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    ) {

        $processName = $stateMachineProcessTransfer->requireProcessName()
            ->getProcessName();

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setProcessName($processName);
        $stateMachineItemTransfer->setIdentifier($identifier);

        $idStateMachineProcess = $this->stateMachinePersistence
            ->getProcessId($stateMachineProcessTransfer);

        $this->assertProcessCreated($idStateMachineProcess);

        $stateMachineItemTransfer->setIdStateMachineProcess($idStateMachineProcess);

        $initialStateName = $this->stateMachineHandlerResolver
            ->get($stateMachineProcessTransfer->getStateMachineName())
            ->getInitialStateForProcess($processName);

        $this->assertInitialStateNameProvided($initialStateName, $processName);
        $stateMachineItemTransfer->setStateName($initialStateName);

        $idStateMachineItemState = $this->stateMachinePersistence
            ->getInitialStateIdByStateName(
                $stateMachineItemTransfer,
                $initialStateName
            );

        $this->assertInitialStateCreated($idStateMachineItemState, $initialStateName);

        $stateMachineItemTransfer->setIdItemState($idStateMachineItemState);

        return $stateMachineItemTransfer;
    }

    /**
     * @param string $initialStateName
     * @param string $processName
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\TriggerException
     *
     * @return void
     */
    protected function assertInitialStateNameProvided($initialStateName, $processName)
    {
        if (!$initialStateName) {
            throw new TriggerException(
                sprintf(
                    'Initial state name for process "%s" is not provided. You can provide it in "%s::getInitialStateForProcess" method.',
                    $processName,
                    StateMachineHandlerInterface::class
                )
            );
        }
    }

    /**
     * @param int|null $idStateMachineItemState
     * @param string $initialStateName
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\TriggerException
     *
     * @return void
     */
    protected function assertInitialStateCreated($idStateMachineItemState, $initialStateName)
    {
        if ($idStateMachineItemState === null) {
            throw new TriggerException(
                sprintf(
                    'Initial state "%s" could not be created.',
                    $initialStateName
                )
            );
        }
    }

    /**
     * @param int $idStateMachineProcess
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\TriggerException
     *
     * @return void
     */
    protected function assertProcessCreated($idStateMachineProcess)
    {
        if (!$idStateMachineProcess) {
            throw new TriggerException(
                sprintf(
                    'Process with name "%s" not found!',
                    $idStateMachineProcess
                )
            );
        }
    }

    /**
     * @param string $commandString
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\CommandNotFoundException
     *
     * @return void
     */
    protected function assertCommandIsSet($commandString, StateMachineHandlerInterface $stateMachineHandler)
    {
        if (!isset($stateMachineHandler->getCommandPlugins()[$commandString])) {
            throw new CommandNotFoundException(
                sprintf(
                    'Command plugin "%s" not registered in "%s" class. Please add it to getCommandPlugins method.',
                    $commandString,
                    get_class($stateMachineHandler)
                )
            );
        }
    }
}
