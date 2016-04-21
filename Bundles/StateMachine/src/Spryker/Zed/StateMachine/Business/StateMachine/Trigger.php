<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Business\Exception\TriggerException;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class Trigger implements TriggerInterface
{

    const MAX_EVENT_REPEATS = 10;

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
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface
     */
    protected $timeout;

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
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface $timeout
     */
    public function __construct(
        TransitionLogInterface $transitionLog,
        HandlerResolverInterface $stateMachineHandlerResolver,
        FinderInterface $finder,
        PersistenceInterface $stateMachinePersistence,
        ConditionInterface $condition,
        StateUpdaterInterface $stateUpdater,
        TimeoutInterface $timeout
    ) {
        $this->transitionLog = $transitionLog;
        $this->stateMachineHandlerResolver = $stateMachineHandlerResolver;
        $this->finder = $finder;
        $this->stateMachinePersistence = $stateMachinePersistence;
        $this->condition = $condition;
        $this->stateUpdater = $stateUpdater;
        $this->timeout = $timeout;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     * @return int
     * @throws \Spryker\Zed\StateMachine\Business\Exception\TriggerException
     */
    public function triggerForNewStateMachineItem(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    ) {
        $stateMachineProcessTransfer->requireStateMachineName()
            ->requireProcessName();

        $stateMachineItemTransfer = $this->createStateMachineItemTransferForNewProcess(
            $stateMachineProcessTransfer,
            $identifier
        );

        $processes = $this->finder->findProcessesForItems(
            $stateMachineProcessTransfer->getStateMachineName(),
            [$stateMachineItemTransfer]
        );

        $itemsWithOnEnterEvent = $this->finder->filterItemsWithOnEnterEvent(
            [$stateMachineItemTransfer],
            $processes
        );

        $this->triggerOnEnterEvents(
            $itemsWithOnEnterEvent,
            $stateMachineProcessTransfer->getStateMachineName()
        );

        return $this->affectedItems;
    }

    /**
     * @param string $eventName
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[]|array $stateMachineItems
     *
     * @return int
     */
    public function triggerEvent($eventName, $stateMachineName, array $stateMachineItems)
    {
        if ($this->checkForEventRepetitions($eventName) === false) {
            return false;
        }

        $stateMachineItems = $this->stateMachinePersistence->updateStateMachineItemsFromPersistence(
            $stateMachineItems,
            $stateMachineName
        );

        $processes = $this->finder->findProcessesForItems($stateMachineName, $stateMachineItems);
        $stateMachineItems = $this->filterEventAffectedItems($eventName, $stateMachineItems, $processes);

        $this->transitionLog->init($stateMachineItems);
        $this->logSourceState($stateMachineItems);

        $this->runCommand($eventName, $stateMachineName, $stateMachineItems, $processes);

        $sourceStateBuffer = $this->updateStateByEvent($eventName, $stateMachineName, $stateMachineItems);

        $this->stateUpdater->updateStateMachineItemState(
            $stateMachineName,
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

        $this->triggerOnEnterEvents($stateMachineItemsWithOnEnterEvent, $stateMachineName);

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
            $stateMachineItemsWithOnEnterEvent = $this->condition->checkConditionsForProcess(
                $stateMachineName,
                $processName
            );
            $this->triggerOnEnterEvents($stateMachineItemsWithOnEnterEvent, $stateMachineName);
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
        $stateMachineItems = $this->timeout->getItemsWithExpiredTimeouts($stateMachineName);

        $groupedStateMachineItems = $this->timeout->groupItemsByEvent($stateMachineItems);
        foreach ($groupedStateMachineItems as $event => $stateMachineItems) {
            $this->triggerEvent($event, $stateMachineName, $stateMachineItems);
        }

        return $this->affectedItems;
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
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     *
     * @throws \Exception
     * @return void
     */
    protected function runCommand($eventName, $stateMachineName, array $stateMachineItems, array $processes)
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

            $this->transitionLog->setEvent($event);
            $commandPlugin = $this->getCommand($event->getCommand(), $stateMachineName);

            try {
                $commandPlugin->run($stateMachineItemTransfer);
            } catch (\Exception $e) {
                $this->transitionLog->setIsError(true);
                $this->transitionLog->setErrorMessage(get_class($commandPlugin) . ' - ' . $e->getMessage());
                $this->transitionLog->saveAll();
                throw $e;
            }
        }
    }

    /**
     * @param string $eventName
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @return array
     */
    protected function updateStateByEvent($eventName, $stateMachineName, array $stateMachineItems)
    {
        $sourceStateBuffer = [];
        $targetStateMap = [];
        foreach ($stateMachineItems as $i => $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->requireStateName()->getStateName();
            $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()] = $stateName;

            $process = $this->finder->findProcessByStateMachineAndProcessName(
                $stateMachineName,
                $stateMachineItemTransfer->getProcessName()
            );

            $sourceState = $process->getStateFromAllProcesses($stateName);
            $this->transitionLog->addSourceState($stateMachineItemTransfer, $sourceState->getName());

            $targetState = $sourceState;
            if (isset($eventName) && $sourceState->hasEvent($eventName)) {
                $transitions = $sourceState->getEvent($eventName)->getTransitionsBySource($sourceState);
                $targetState = $this->condition->checkConditionForTransitions(
                    $stateMachineName,
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
            $this->stateMachinePersistence->saveStateMachineItemState($stateMachineItems[$i], $targetStateMap[$i]);
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
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $itemsWithOnEnterEvent
     * @param string $stateMachineName
     * @return bool
     */
    protected function triggerOnEnterEvents(array $itemsWithOnEnterEvent, $stateMachineName)
    {
        if (count($itemsWithOnEnterEvent) > 0) {
            foreach ($itemsWithOnEnterEvent as $eventName => $stateMachineItems) {
                $this->triggerEvent($eventName, $stateMachineName, $stateMachineItems);
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $commandString
     * @param string $stateMachineName
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface
     */
    protected function getCommand($commandString, $stateMachineName)
    {
        $stateMachineHandler = $this->stateMachineHandlerResolver->get($stateMachineName);

        // TODO FW Extract to assert*() method
        if (!isset($stateMachineHandler->getCommandPlugins()[$commandString])) {

            // TODO FW Use your own exception, not from Symfony
            throw new CommandNotFoundException(
                sprintf(
                    'Command plugin "%s" not registered in "%s" class. Please add it to getCommandPlugins method.',
                    $commandString,
                    get_class($stateMachineHandler)
                )
            );
        }

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
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     * @throws \Spryker\Zed\StateMachine\Business\Exception\TriggerException
     */
    protected function createStateMachineItemTransferForNewProcess(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    ) {

        $processName = $stateMachineProcessTransfer->getProcessName();

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setProcessName($processName);
        $stateMachineItemTransfer->setIdentifier($identifier);

        $idStateMachineProcess = $this->stateMachinePersistence->getProcessId($stateMachineProcessTransfer);

        // TODO FW Extract to assert*() method
        if (!$idStateMachineProcess) {
            throw new TriggerException(
                sprintf(
                    'Process with name "%s" not found!',
                    $idStateMachineProcess
                )
            );
        }

        $stateMachineItemTransfer->setIdStateMachineProcess($idStateMachineProcess);

        $initialStateName = $this->stateMachineHandlerResolver
            ->get($stateMachineProcessTransfer->getStateMachineName())
            ->getInitialStateForProcess($processName);

        // TODO FW Extract to assert*() method
        if (!$initialStateName) {
            throw new TriggerException(
                sprintf(
                    'Initial state name for process "%s" is not provided. Please implement this in "%s::getInitialStateForProcess" method.',
                    $processName,
                    StateMachineHandlerInterface::class
                )
            );
        }
        $stateMachineItemTransfer->setStateName($initialStateName);

        $idStateMachineItemState = $this->stateMachinePersistence->getInitialStateIdByStateName(
            $initialStateName,
            $idStateMachineProcess
        );

        // TODO FW Extract to assert*() method
        if ($idStateMachineItemState === null) {
            throw new TriggerException(
                sprintf(
                    'Initial state "%s" could not be created.',
                    $initialStateName
                )
            );
        }

        $stateMachineItemTransfer->setIdItemState($idStateMachineItemState);

        return $stateMachineItemTransfer;
    }

}
