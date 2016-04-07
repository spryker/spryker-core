<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use DateTime;
use Exception;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use LogicException;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Propel\Runtime\Propel;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;
use Spryker\Zed\StateMachine\Business\Process\StateInterface;
use Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;

class StateMachine implements StateMachineInterface
{
    const MAX_EVENT_REPEATS = 10;
    const MAX_ON_ENTER = 50;

    /**
     * @var array
     */
    protected $eventCounter = [];

    /**
     * @var array
     */
    protected $processBuffer = [];

    /**
     * @var array
     */
    protected $states = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface
     */
    protected $timeout;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface
     */
    protected $transitionLog;

    /**
     * @var StateMachineHandlerInterface
     */
    protected $stateMachineHandler;

    /**
     * @var StateMachineQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var FinderInterface
     */
    protected $finder;

    /**
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @param StateMachineQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface $builder
     * @param \Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface $transitionLog
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface $timeout
     * @param StateMachineHandlerInterface $stateMachineHandler
     * @param FinderInterface $finder
     */
    public function __construct(
        StateMachineQueryContainerInterface $queryContainer,
        BuilderInterface $builder,
        TransitionLogInterface $transitionLog,
        TimeoutInterface $timeout,
        StateMachineHandlerInterface $stateMachineHandler,
        FinderInterface $finder,
        PersistenceManagerInterface $persistenceManager
    ) {
        $this->queryContainer = $queryContainer;
        $this->builder = $builder;
        $this->transitionLog = $transitionLog;
        $this->timeout = $timeout;
        $this->stateMachineHandler = $stateMachineHandler;
        $this->finder = $finder;
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @return bool
     */
    public function triggerForNewStateMachineItem(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    ) {
        $stateMachineProcessTransfer->requireStateMachineName();

        $processName = $stateMachineProcessTransfer->getProcessName();

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setProcessName($processName);
        $stateMachineItemTransfer->setIdentifier($identifier);

        $initialStateName = $this->stateMachineHandler->getInitialStateForProcess($processName);
        $stateMachineItemTransfer->setStateName($initialStateName);

        $idStateMachineProcess = $this->persistenceManager->getProcessId($stateMachineProcessTransfer);
        $stateMachineItemTransfer->setIdStateMachineProcess($idStateMachineProcess);

        $initialStateName = $this->stateMachineHandler->getInitialStateForProcess($processName);
        $stateMachineItemTransfer->setStateName($initialStateName);

        $idStateMachineItemState = $this->persistenceManager->getInitialStateIdByStateName(
            $initialStateName,
            $idStateMachineProcess
        );

        $stateMachineItemTransfer->setIdItemState($idStateMachineItemState);

        $processes = $this->getProcesses([$stateMachineItemTransfer]);
        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent([$stateMachineItemTransfer], $processes);
        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent);
    }

    /**
     * @param string $eventName
     * @param StateMachineItemTransfer[]|array $stateMachineItems
     *
     * @return bool
     */
    public function triggerEvent($eventName, array $stateMachineItems)
    {
        if ($this->checkForEventRepetitions($eventName) === false) {
            return false;
        }

        $stateMachineItems = $this->finder->getStateMachineItemsFromPersistence($stateMachineItems);

        $processes = $this->getProcesses($stateMachineItems);

        $stateMachineItems = $this->filterAffectedItems($eventName, $stateMachineItems, $processes);

        $log = $this->initTransitionLog($stateMachineItems);

        $this->logSourceState($stateMachineItems, $log);

        $this->runCommand($eventName, $stateMachineItems, $processes, $log);

        $sourceStateBuffer = $this->updateStateByEvent($eventName, $stateMachineItems, $log);

        $this->saveItems($stateMachineItems, $processes, $sourceStateBuffer);

        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent($stateMachineItems, $processes, $sourceStateBuffer);

        $log->saveAll();

        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent);

        return true;
    }

    /**
     * @return int
     */
    public function checkConditions()
    {
        $affectedOrderItems = 0;
        foreach ($this->stateMachineHandler->getActiveProcesses() as $processName) {
            $stateMachineProcessTransfer = new StateMachineProcessTransfer();
            $stateMachineProcessTransfer->setStateMachineName($this->stateMachineHandler->getStateMachineName());
            $stateMachineProcessTransfer->setProcessName($processName);

            $process = $this->builder->createProcess($stateMachineProcessTransfer);
            $orderStateMachine = clone $this;
            $affectedOrderItems += $orderStateMachine->checkConditionsForProcess($process);
        }

        return $affectedOrderItems;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     * @return int
     */
    protected function checkConditionsForProcess(ProcessInterface $process)
    {
        $transitions = $process->getAllTransitionsWithoutEvent();

        $stateToTransitionsMap = $this->createStateToTransitionMap($transitions);

        $stateMachineStateItems = $this->queryContainer
            ->queryStateMachineItemsByState(array_keys($stateToTransitionsMap), 3)
            ->find();

        $stateMachineItemStateIds = [];
        foreach ($stateMachineStateItems as $stateMachineItemEntity) {
            $stateMachineItemStateIds[] = $stateMachineItemEntity->getIdStateMachineItemState();
        }

        $stateMachineItems = $this->stateMachineHandler->getStateMachineItemsByStateIds($stateMachineItemStateIds);
        $stateMachineItems = $this->finder->getStateMachineItemsFromPersistence($stateMachineItems);

        $countAffectedItems = count($stateMachineItems);
        if ($countAffectedItems === 0) {
            return 0;
        }

        $log = $this->initTransitionLog($stateMachineItems);

        $sourceStateBuffer = $this->updateStateByTransition($stateToTransitionsMap, $stateMachineItems, [], $log);

        $processes = [$process->getName() => $process];

        $this->saveItems($stateMachineItems, $processes, $sourceStateBuffer);

        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent($stateMachineItems, $processes, $sourceStateBuffer);

        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent);

        return $countAffectedItems;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $transitions
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $sourceState
     * @param \Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface $log
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    protected function checkCondition(
        array $transitions,
        StateMachineItemTransfer $stateMachineItemTransfer,
        StateInterface $sourceState,
        TransitionLogInterface $log
    ) {
        $possibleTransitions = [];

        foreach ($transitions as $transition) {
            if ($transition->hasCondition()) {
                $conditionString = $transition->getCondition();
                $conditionModel = $this->getCondition($conditionString);

                try {
                    $conditionCheck = $conditionModel->check($stateMachineItemTransfer);
                } catch (Exception $e) {
                    $log->setIsError(true);
                    $log->setErrorMessage(get_class($e) . ' - ' . $e->getMessage());
                    $log->saveAll();
                    throw $e;
                }

                if ($conditionCheck === true) {
                    $log->addCondition($stateMachineItemTransfer, $conditionModel);
                    array_unshift($possibleTransitions, $transition);
                }
            } else {
                array_push($possibleTransitions, $transition);
            }
        }

        if (count($possibleTransitions) > 0) {
            /** @var \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $selectedTransition */
            $selectedTransition = array_shift($possibleTransitions);
            $targetState = $selectedTransition->getTarget();
        } else {
            $targetState = $sourceState;
        }

        return $targetState;
    }

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    protected function getProcesses(array $stateMachineItems)
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
     * Filters out all items that are not affected by the current event
     *
     * @param string $eventId
     * @param StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     *
     * @return StateMachineItemTransfer[]
     */
    protected function filterAffectedItems($eventId, array $stateMachineItems, $processes)
    {
        $stateMachineItemsFiltered = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->getStateName();
            $processName = $stateMachineItemTransfer->getProcessName();
            $process = $processes[$processName];

            $state = $process->getStateFromAllProcesses($stateName);

            if ($state->hasEvent($eventId)) {
                $stateMachineItemsFiltered[] = $stateMachineItemTransfer;
            }
        }

        return $stateMachineItemsFiltered;
    }


    /**
     * @param string $eventId
     * @param StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param \Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface $log
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function runCommand($eventId, array $stateMachineItems, array $processes, TransitionLogInterface $log)
    {
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->getStateName();
            $processName = $stateMachineItemTransfer->getProcessName();
            $process = $processes[$processName];
            $state = $process->getStateFromAllProcesses($stateName);
            $event = $state->getEvent($eventId);

            if (!$event->hasCommand()) {
                continue;
            }

            $log->setEvent($event);
            $command = $this->getCommand($event->getCommand());

            try {
                $command->run($stateMachineItemTransfer);
            } catch (Exception $e) {
                $log->setIsError(true);
                $log->setErrorMessage(get_class($e) . ' - ' . $e->getMessage());
                $log->saveAll();
                throw $e;
            }
        }
    }

    /**
     * @param string $eventId
     * @param StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface $log
     *
     * @return array
     */
    protected function updateStateByEvent(
        $eventId,
        array $stateMachineItems,
        TransitionLogInterface $log
    ) {

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

            $log->addSourceState($stateMachineItemTransfer, $sourceState->getName());

            $targetState = $sourceState;
            if (isset($eventId) && $sourceState->hasEvent($eventId)) {
                $transitions = $sourceState->getEvent($eventId)->getTransitionsBySource($sourceState);
                $targetState = $this->checkCondition($transitions, $stateMachineItemTransfer, $sourceState, $log);
                $log->addTargetState($stateMachineItemTransfer, $targetState->getName());
            }

            $targetStateMap[$i] = $targetState->getName();
        }

        foreach ($stateMachineItems as $i => $stateMachineItemTransfer) {
            $this->setState($stateMachineItems[$i], $targetStateMap[$i]);
        }

        return $sourceStateBuffer;
    }

    /**
     * @param array $stateToTransitionsMap
     * @param StateMachineItemTransfer[] $stateMachineItems
     * @param array $sourceStateBuffer
     * @param \Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface $log
     *
     * @return array
     */
    protected function updateStateByTransition($stateToTransitionsMap, array $stateMachineItems, array $sourceStateBuffer, TransitionLogInterface $log)
    {
        $targetStateMap = [];
        foreach ($stateMachineItems as $i => $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->getStateName();
            $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()] = $stateName;

            $stateMachineProcessTransfer = new StateMachineProcessTransfer();
            $stateMachineProcessTransfer->setStateMachineName($this->stateMachineHandler->getStateMachineName());
            $stateMachineProcessTransfer->setProcessName($stateMachineItemTransfer->getProcessName());

            $process = $this->builder->createProcess($stateMachineProcessTransfer);
            $sourceState = $process->getStateFromAllProcesses($stateName);

            $log->addSourceState($stateMachineItemTransfer, $sourceState->getName());

            $transitions = $stateToTransitionsMap[$stateMachineItemTransfer->getStateName()];

            $targetState = $sourceState;
            if (count($transitions) > 0) {
                $targetState = $this->checkCondition($transitions, $stateMachineItemTransfer, $sourceState, $log);
            }

            $log->addTargetState($stateMachineItemTransfer, $targetState->getName());

            $targetStateMap[$i] = $targetState->getName();
        }

        foreach ($stateMachineItems as $i => $stateMachineItemTransfer) {
            $this->setState($stateMachineItems[$i], $targetStateMap[$i]);
        }

        return $sourceStateBuffer;
    }

    /**
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return void
     */
    protected function setState(StateMachineItemTransfer $stateMachineItemTransfer, $stateName)
    {
        if (isset($this->states[$stateName])) {
            $stateMachineItemStateEntity = $this->states[$stateName];
        } else {
            $stateMachineItemStateEntity = SpyStateMachineItemStateQuery::create()->findOneByName($stateName);
            if (!isset($stateMachineItemStateEntity)) {
                $stateMachineItemStateEntity = new SpyStateMachineItemState();
                $stateMachineItemStateEntity->setName($stateName);
                $stateMachineItemStateEntity->setFkStateMachineProcess($stateMachineItemTransfer->getIdStateMachineProcess());
                $stateMachineItemStateEntity->save();
            }
            $this->states[$stateName] = $stateMachineItemStateEntity;
        }

        $stateMachineItemTransfer->setIdItemState($stateMachineItemStateEntity->getIdStateMachineItemState());
        $stateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());
    }

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param array $sourceStateBuffer
     *
     * @throws \LogicException
     *
     * @return array
     */
    protected function filterItemsWithOnEnterEvent(
        array $stateMachineItems,
        array $processes,
        array $sourceStateBuffer = []
    ) {
        $orderItemsWithOnEnterEvent = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->getStateName();
            $processName = $stateMachineItemTransfer->getProcessName();

            if (!isset($processes[$processName])) {
                throw new LogicException("Unknown process $processName");
            }

            $process = $processes[$processName];
            $targetState = $process->getStateFromAllProcesses($stateName);

            if (isset($sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()])) {
                $sourceState = $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()];
            } else {
                $sourceState = $process->getStateFromAllProcesses($stateMachineItemTransfer->getStateName());
            }

            if ($sourceState !== $targetState->getName()
                && $targetState->hasOnEnterEvent()
            ) {
                $event = $targetState->getOnEnterEvent();
                if (array_key_exists($event->getName(), $orderItemsWithOnEnterEvent) === false) {
                    $orderItemsWithOnEnterEvent[$event->getName()] = [];
                }
                $orderItemsWithOnEnterEvent[$event->getName()][] = $stateMachineItemTransfer;
            }
        }

        return $orderItemsWithOnEnterEvent;
    }

    /**
     * To protect of loops, every event can only be used some times
     *
     * @param string $eventId
     *
     * @return bool
     */
    protected function checkForEventRepetitions($eventId)
    {
        if (array_key_exists($eventId, $this->eventCounter) === false) {
            $this->eventCounter[$eventId] = 0;
        }
        $this->eventCounter[$eventId]++;

        return $this->eventCounter[$eventId] < self::MAX_EVENT_REPEATS;
    }

    /**
     * @param array $itemsWithOnEnterEvent
     *
     * @return void
     */
    protected function triggerOnEnterEvents(
        array $itemsWithOnEnterEvent
    ) {
        if (count($itemsWithOnEnterEvent) > 0) {
            foreach ($itemsWithOnEnterEvent as $eventId => $stateMachineItems) {
                $this->triggerEvent($eventId, $stateMachineItems);
            }
        }
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $transitions
     *
     * @return array
     */
    protected function createStateToTransitionMap(array $transitions)
    {
        $stateToTransitionsMap = [];
        foreach ($transitions as $transition) {
            $sourceStateName = $transition->getSource()->getName();
            if (array_key_exists($sourceStateName, $stateToTransitionsMap) === false) {
                $stateToTransitionsMap[$sourceStateName] = [];
            }
            $stateToTransitionsMap[$sourceStateName][] = $transition;
        }

        return $stateToTransitionsMap;
    }

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param array $sourceStateBuffer
     *
     * @return void
     */
    protected function saveItems(array $stateMachineItems, array $processes, array $sourceStateBuffer)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $currentTime = new DateTime('now');

        $timeoutModel = clone $this->timeout;

        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $process = $processes[$stateMachineItemTransfer->getProcessName()];

            $sourceState = $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()];
            $targetState = $stateMachineItemTransfer->getStateName();

            if ($sourceState !== $targetState) {
                $timeoutModel->dropOldTimeout($process, $sourceState, $stateMachineItemTransfer);
                $timeoutModel->setNewTimeout($process, $stateMachineItemTransfer, $currentTime);
                $this->stateMachineHandler->itemStateUpdated($stateMachineItemTransfer);

                $stateMachineItemStateHistory = new SpyStateMachineItemStateHistory();
                $stateMachineItemStateHistory->setIdentifier($stateMachineItemTransfer->getIdentifier());
                $stateMachineItemStateHistory->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState());
                $stateMachineItemStateHistory->save();

            }
        }

        $connection->commit();
    }

    /**
     * @param string $commandString
     *
     * @throws \LogicException
     *
     * @return CommandPluginInterface
     */
    protected function getCommand($commandString)
    {
        if (!isset($this->stateMachineHandler->getCommandPlugins()[$commandString])) {
            throw new LogicException('Command ' . $commandString . ' not found in Settings');
        }

        return $this->stateMachineHandler->getCommandPlugins()[$commandString];
    }

    /**
     * @param string $conditionString
     *
     * @return ConditionPluginInterface
     */
    protected function getCondition($conditionString)
    {
        if (!isset($this->stateMachineHandler->getConditionPlugins()[$conditionString])) {
            throw new LogicException('Condition ' . $conditionString . ' not found in Settings');
        }

        return $this->stateMachineHandler->getConditionPlugins()[$conditionString];
    }

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface
     */
    protected function initTransitionLog(array $stateMachineItems)
    {
        $transitionLogEntity = clone $this->transitionLog;

        $transitionLogEntity->init($stateMachineItems);

        return $transitionLogEntity;
    }

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface $log
     *
     * @return void
     */
    protected function logSourceState(array $stateMachineItems, TransitionLogInterface $log)
    {
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->getStateName();
            $log->addSourceState($stateMachineItemTransfer, $stateName);
        }
    }

}
