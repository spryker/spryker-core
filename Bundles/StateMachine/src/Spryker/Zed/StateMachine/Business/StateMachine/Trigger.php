<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Exception;
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
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface
     */
    protected $transitionLog;

    /**
     * @var \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface
     */
    protected $stateMachineHandler;

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
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface $builder
     * @param \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface $transitionLog
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface $finder
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface $stateMachinePersistence
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface $condition
     */
    public function __construct(
        BuilderInterface $builder,
        TransitionLogInterface $transitionLog,
        StateMachineHandlerInterface $stateMachineHandler,
        FinderInterface $finder,
        PersistenceInterface $stateMachinePersistence,
        ConditionInterface $condition
    ) {
        $this->builder = $builder;
        $this->transitionLog = $transitionLog;
        $this->stateMachineHandler = $stateMachineHandler;
        $this->finder = $finder;
        $this->stateMachinePersistence = $stateMachinePersistence;
        $this->condition = $condition;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @return bool
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

        $processes = $this->getProcessesForItems([$stateMachineItemTransfer]);

        $itemsWithOnEnterEvent = $this->finder->filterItemsWithOnEnterEvent(
            [$stateMachineItemTransfer],
            $processes
        );

        return $this->triggerOnEnterEvents($itemsWithOnEnterEvent);
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[]|array $stateMachineItems
     *
     * @return bool
     */
    public function triggerEvent($eventName, array $stateMachineItems)
    {
        if ($this->checkForEventRepetitions($eventName) === false) {
            return false;
        }

        $stateMachineItems = $this->stateMachinePersistence->updateStateMachineItemsFromPersistence($stateMachineItems);

        $processes = $this->getProcessesForItems($stateMachineItems);

        $stateMachineItems = $this->filterEventAffectedItems($eventName, $stateMachineItems, $processes);

        $this->transitionLog->init($stateMachineItems);

        $this->logSourceState($stateMachineItems);

        $this->runCommand($eventName, $stateMachineItems, $processes);

        $sourceStateBuffer = $this->updateStateByEvent($eventName, $stateMachineItems);

        $this->stateMachinePersistence->updateStateMachineItemState($stateMachineItems, $processes, $sourceStateBuffer);

        $stateMachineItemsWithOnEnterEvent = $this->finder->filterItemsWithOnEnterEvent(
            $stateMachineItems,
            $processes,
            $sourceStateBuffer
        );

        $this->transitionLog->saveAll();

        return $this->triggerOnEnterEvents($stateMachineItemsWithOnEnterEvent);
    }

    /**
     * @return int
     */
    public function triggerConditionsWithoutEvent()
    {
        $affectedItems = 0;
        foreach ($this->stateMachineHandler->getActiveProcesses() as $processName) {
            $stateMachineProcessTransfer = new StateMachineProcessTransfer();
            $stateMachineProcessTransfer->setStateMachineName($this->stateMachineHandler->getStateMachineName());
            $stateMachineProcessTransfer->setProcessName($processName);

            $process = $this->builder->createProcess($stateMachineProcessTransfer);
            $condition = clone $this->condition;
            $stateMachineItemsWithOnEnterEvent = $condition->checkConditionsForProcess($process);
            $this->triggerOnEnterEvents($stateMachineItemsWithOnEnterEvent);
        }

        return $affectedItems;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    protected function getProcessesForItems(array $stateMachineItems)
    {
        $processes = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            if (array_key_exists($stateMachineItemTransfer->getProcessName(), $processes) === false) {

                $stateMachineProcessTransfer = new StateMachineProcessTransfer();
                $stateMachineProcessTransfer->setStateMachineName($this->stateMachineHandler->getStateMachineName());
                $stateMachineProcessTransfer->setProcessName($stateMachineItemTransfer->getProcessName());

                $processes[$stateMachineItemTransfer->getProcessName()] = $this->builder->createProcess($stateMachineProcessTransfer);
            }
        }

        return $processes;
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
            $stateName = $stateMachineItemTransfer->getStateName();
            $processName = $stateMachineItemTransfer->getProcessName();
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
            $stateName = $stateMachineItemTransfer->getStateName();
            $processName = $stateMachineItemTransfer->getProcessName();
            $process = $processes[$processName];
            $state = $process->getStateFromAllProcesses($stateName);
            $event = $state->getEvent($eventName);

            if (!$event->hasCommand()) {
                continue;
            }

            $this->transitionLog->setEvent($event);
            $commandPlugin = $this->getCommand($event->getCommand());

            try {
                $commandPlugin->run($stateMachineItemTransfer);
            } catch (Exception $e) {
                $this->transitionLog->setIsError(true);
                $this->transitionLog->setErrorMessage(get_class($e) . ' - ' . $e->getMessage());
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
            $stateName = $stateMachineItemTransfer->getStateName();
            $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()] = $stateName;

            $stateMachineProcessTransfer = new StateMachineProcessTransfer();
            $stateMachineProcessTransfer->setStateMachineName($this->stateMachineHandler->getStateMachineName());
            $stateMachineProcessTransfer->setProcessName($stateMachineItemTransfer->getProcessName());

            $process = $this->builder->createProcess($stateMachineProcessTransfer);
            $sourceState = $process->getStateFromAllProcesses($stateName);

            $this->transitionLog->addSourceState($stateMachineItemTransfer, $sourceState->getName());

            $targetState = $sourceState;
            if (isset($eventName) && $sourceState->hasEvent($eventName)) {
                $transitions = $sourceState->getEvent($eventName)->getTransitionsBySource($sourceState);
                $targetState = $this->condition->checkConditionForTransitions(
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
     *
     * @throws \LogicException
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface
     */
    protected function getCommand($commandString)
    {
        if (!isset($this->stateMachineHandler->getCommandPlugins()[$commandString])) {
            throw new CommandNotFoundException(
                sprintf(
                    'Command plugin "%s" not registered in "%s" class. Please add it to getCommandPlugins method.',
                    $commandString,
                    get_class($this->stateMachineHandler)
                )
            );
        }

        return $this->stateMachineHandler->getCommandPlugins()[$commandString];
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
        if (!$idStateMachineProcess) {
            throw new TriggerException(
                sprintf(
                    'Process with name "%s" not found!',
                    $idStateMachineProcess
                )
            );
        }

        $stateMachineItemTransfer->setIdStateMachineProcess($idStateMachineProcess);

        $initialStateName = $this->stateMachineHandler->getInitialStateForProcess($processName);
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
