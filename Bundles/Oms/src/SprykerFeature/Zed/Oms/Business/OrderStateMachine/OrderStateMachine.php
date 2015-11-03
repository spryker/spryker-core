<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use SprykerFeature\Shared\Library\Log;
use SprykerFeature\Zed\Oms\Business\Process\ProcessInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByItemInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use SprykerFeature\Zed\Oms\Business\Process\StateInterface;
use SprykerFeature\Zed\Oms\Business\Process\TransitionInterface;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Business\Util\TransitionLogInterface;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use DateTime;
use Exception;
use LogicException;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;

class OrderStateMachine implements OrderStateMachineInterface
{

    const BY_ITEM = 'byItem';
    const BY_ORDER = 'byOrder';
    const MAX_EVENT_REPEATS = 10;
    const MAX_ON_ENTER = 50;

    protected $eventCounter = [];

    protected $returnData = [];

    protected $processBuffer = [];

    protected $states = [];

    /**
     * @var OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var TimeoutInterface
     */
    protected $timeout;

    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @var TransitionLogInterface
     */
    protected $transitionLog;

    /**
     * @var ReadOnlyArrayObject
     */
    protected $activeProcesses;

    /**
     * @var array
     */
    protected $conditions;

    /**
     * @var array
     */
    protected $commands;

    /**
     * @param OmsQueryContainerInterface $queryContainer
     * @param BuilderInterface $builder
     * @param TransitionLogInterface $transitionLog
     * @param TimeoutInterface $timeout
     * @param ReadOnlyArrayObject $activeProcesses
     * @param array $conditions
     * @param array $commands
     */
    public function __construct(
        OmsQueryContainerInterface $queryContainer,
        BuilderInterface $builder,
        TransitionLogInterface $transitionLog,
        TimeoutInterface $timeout,
        ReadOnlyArrayObject $activeProcesses,
        array $conditions,
        array $commands
    ) {
        $this->queryContainer = $queryContainer;
        $this->builder = $builder;
        $this->transitionLog = $transitionLog;
        $this->timeout = $timeout;
        $this->activeProcesses = $activeProcesses;
        $this->conditions = $conditions;
        $this->commands = $commands;
    }

    /**
     * @param string $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, array $orderItems, $data)
    {
        assert('is_string($eventId)');
        assert('count($orderItems) > 0');

        if (false === $this->checkForEventRepetitions($eventId)) {
            return [];
        }

        $data = $this->makeDataReadOnly($data);

        $processes = $this->getProcesses($orderItems);

        $orderItems = $this->filterAffectedOrderItems($eventId, $orderItems, $processes);

        $log = $this->initTransitionLog($orderItems);
        //$log->addItems($orderItems);

        $orderGroup = $this->groupByOrderAndState($eventId, $orderItems, $processes);
        $sourceStateBuffer = [];
        foreach ($orderGroup as $groupedOrderItems) {
            $this->logSourceState($groupedOrderItems, $log);

            $this->runCommand($eventId, $groupedOrderItems, $processes, $data, $log);
            $sourceStateBuffer = $this->updateStateByEvent($eventId, $groupedOrderItems, $sourceStateBuffer, $log);
            $this->saveOrderItems($groupedOrderItems, $log, $processes, $sourceStateBuffer);
        }

        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent($orderItems, $processes, $sourceStateBuffer);

        $log->saveAll();

        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent, $data);

        return $this->returnData;
    }

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, $data)
    {
        $orderItems = $this->queryContainer
            ->querySalesOrderItems($orderItemIds)
            ->find()
            ->getData();

        return $this->triggerEvent($eventId, $orderItems, $data);
    }

    /**
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, $data)
    {
        $orderItems = $this->queryContainer
            ->querySalesOrderItems([$orderItemId])
            ->find()
            ->getData();

        return $this->triggerEvent($eventId, $orderItems, $data);
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewItem(array $orderItems, $data)
    {
        $data = $this->makeDataReadOnly($data);
        $sourceStateBuffer = [];
        $processes = $this->getProcesses($orderItems);
        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent($orderItems, $processes, $sourceStateBuffer);
        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent, $data);

        return $this->returnData;
    }

    /**
     * @param int[] $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, $data)
    {
        $orderItems = $this->queryContainer
            ->querySalesOrderItems($orderItemIds)
            ->find()
            ->getData();

        return $this->triggerEventForNewItem($orderItems, $data);
    }

    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkConditions(array $logContext = [])
    {
        $affectedOrderItems = 0;
        foreach ($this->activeProcesses as $processName) {
            $process = $this->builder->createProcess($processName);
            $orderStateMachine = clone $this;
            $affectedOrderItems += $orderStateMachine->checkConditionsForProcess($process);
        }

        return $affectedOrderItems;
    }

    /**
     * @param ProcessInterface $process
     * 
     * @return int
     */
    protected function checkConditionsForProcess(ProcessInterface $process)
    {
        $transitions = $process->getAllTransitionsWithoutEvent();

        $stateToTransitionsMap = $this->createStateToTransitionMap($transitions);

        $orderItems = $this->getOrderItemsByState(array_keys($stateToTransitionsMap), $process);

        $countAffectedItems = count($orderItems);

        if (count($orderItems) === 0) {
            return 0;
        }

        $log = $this->initTransitionLog($orderItems);

        $sourceStateBuffer = $this->updateStateByTransition($stateToTransitionsMap, $orderItems, [], $log);

        $processes = [$process->getName() => $process];

        $this->saveOrderItems($orderItems, $log, $processes, $sourceStateBuffer);

        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent($orderItems, $processes, $sourceStateBuffer);

        $data = $this->makeDataReadOnly([]);

        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent, $data);

        return $countAffectedItems;
    }

    /**
     * @param TransitionInterface[] $transitions
     * @param SpySalesOrderItem $orderItem
     * @param StateInterface $sourceState
     * @param TransitionLogInterface $log
     *
     * @throws Exception
     *
     * @return StateInterface
     */
    protected function checkCondition(array $transitions, $orderItem, StateInterface $sourceState, TransitionLogInterface $log)
    {
        $possibleTransitions = [];

        foreach ($transitions as $transition) {
            if ($transition->hasCondition()) {
                $conditionString = $transition->getCondition();
                $conditionModel = $this->getCondition($conditionString);

                try {
                    $conditionCheck = $conditionModel->check($orderItem);
                } catch (Exception $e) {
                    $log->setError(true);
                    $log->setErrorMessage(get_class($e) . ' - ' . $e->getMessage());
                    $log->saveAll();
                    throw $e;
                }

                if ($conditionCheck === true) {
                    $log->addCondition($orderItem, $conditionModel);
                    array_unshift($possibleTransitions, $transition);
                }
            } else {
                array_push($possibleTransitions, $transition);
            }
        }

        if (count($possibleTransitions) > 0) {
            $selectedTransition = array_shift($possibleTransitions);
            $targetState = $selectedTransition->getTarget();
        } else {
            $targetState = $sourceState;
        }

        return $targetState;
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     *
     * @return ProcessInterface[]
     */
    protected function getProcesses(array $orderItems)
    {
        $processes = [];
        foreach ($orderItems as $orderItem) {
            $processName = $orderItem->getProcess()->getName();
            if (false === array_key_exists($processName, $processes)) {
                $processes[$processName] = $this->builder->createProcess($processName);
            }
        }

        return $processes;
    }

    /**
     * Filters out all items that are not affected by the current event
     *
     * @param string $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param ProcessInterface[] $processes
     *
     * @return SpySalesOrderItem[]
     */
    protected function filterAffectedOrderItems($eventId, array $orderItems, $processes)
    {
        $orderItemsFiltered = [];
        foreach ($orderItems as $orderItem) {
            $stateId = $orderItem->getState()->getName();
            $processId = $orderItem->getProcess()->getName();
            $process = $processes[$processId];

            $state = $process->getStateFromAllProcesses($stateId);

            if ($state->hasEvent($eventId)) {
                $orderItemsFiltered[] = $orderItem;
            }
        }

        return $orderItemsFiltered;
    }

    /**
     * @param string $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param ProcessInterface[] $processes
     *
     * @return array
     */
    protected function groupByOrderAndState($eventId, array $orderItems, $processes)
    {
        $orderEventGroup = [];
        foreach ($orderItems as $orderItem) {
            $stateId = $orderItem->getState()->getName();
            $processId = $orderItem->getProcess()->getName();
            $process = $processes[$processId];
            $orderId = $orderItem->getOrder()->getIdSalesOrder();

            $state = $process->getStateFromAllProcesses($stateId);

            if ($state->hasEvent($eventId)) {
                $key = $orderId . '-' . $stateId;
                if (!isset($orderEventGroup[$key])) {
                    $orderEventGroup[$key] = [];
                }
                $orderEventGroup[$key][] = $orderItem;
            }
        }

        return $orderEventGroup;
    }

    /**
     * @param CommandInterface $command
     *
     * @throws LogicException
     *
     * @return string
     */
    protected function getCommandType(CommandInterface $command)
    {
        if ($command instanceof CommandByOrderInterface) {
            return self::BY_ORDER;
        } elseif ($command instanceof CommandByItemInterface) {
            return self::BY_ITEM;
        } else {
            throw new LogicException('Unknown type of command: ' . get_class($command));
        }
    }

    /**
     * @param string $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param ProcessInterface[] $processes
     * @param ReadOnlyArrayObject $data
     * @param TransitionLogInterface $log
     *
     * @throws Exception
     *
     * @return void
     */
    protected function runCommand($eventId, array $orderItems, array $processes, ReadOnlyArrayObject $data, TransitionLogInterface $log)
    {
        $orderEntity = current($orderItems)->getOrder();
        foreach ($orderItems as $orderItem) {
            $stateId = $orderItem->getState()->getName();
            $processId = $orderItem->getProcess()->getName();
            $process = $processes[$processId];
            $state = $process->getStateFromAllProcesses($stateId);
            $event = $state->getEvent($eventId);

            $log->setEvent($event);

            if ($event->hasCommand()) {
                $command = $this->getCommand($event->getCommand());
                $type = $this->getCommandType($command);

                $log->addCommand($orderItem, $command);

                try {
                    if ($type === self::BY_ITEM) {
                        $returnData = $command->run($orderItem, $data);
                        $this->returnData = array_merge($this->returnData, $returnData);
                    } else {
                        $returnData = $command->run($orderItems, $orderEntity, $data);
                        if (is_array($returnData)) {
                            $this->returnData = array_merge($this->returnData, $returnData);
                        }
                        break;
                    }
                } catch (Exception $e) {
                    $log->setError(true);
                    $log->setErrorMessage(get_class($e) . ' - ' . $e->getMessage());
                    $log->saveAll();
                    throw $e;
                }
            }
        }
    }

    /**
     * @param string $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param array $sourceStateBuffer
     * @param TransitionLogInterface $log
     *
     * @return array
     */
    protected function updateStateByEvent($eventId, array $orderItems, array $sourceStateBuffer, TransitionLogInterface $log)
    {
        assert(is_string($eventId) || is_null($eventId));
        if (is_null($sourceStateBuffer)) {
            $sourceStateBuffer = [];
        }

        $targetStateMap = [];
        foreach ($orderItems as $i => $orderItem) {
            $stateId = $orderItem->getState()->getName();
            $sourceStateBuffer[$orderItem->getIdSalesOrderItem()] = $stateId;

            $process = $this->builder->createProcess($orderItem->getProcess()->getName());
            $sourceState = $process->getStateFromAllProcesses($stateId);

            //$log->addSourceState($orderItem, $sourceState->getName());

            $targetState = $sourceState;
            if (isset($eventId) && $sourceState->hasEvent($eventId)) {
                $transitions = $sourceState->getEvent($eventId)->getTransitionsBySource($sourceState);
                $targetState = $this->checkCondition($transitions, $orderItem, $sourceState, $log);
                $log->addTargetState($orderItem, $targetState->getName());
            }

            $targetStateMap[$i] = $targetState->getName();
        }

        foreach ($orderItems as $i => $orderItem) {
            $this->setState($orderItems[$i], $targetStateMap[$i]);
        }

        return $sourceStateBuffer;
    }

    /**
     * @param array $stateToTransitionsMap
     * @param SpySalesOrderItem[] $orderItems
     * @param array $sourceStateBuffer
     * @param TransitionLogInterface $log
     *
     * @return array
     */
    protected function updateStateByTransition($stateToTransitionsMap, array $orderItems, array $sourceStateBuffer, TransitionLogInterface $log)
    {
        if (is_null($sourceStateBuffer)) {
            $sourceStateBuffer = [];
        }
        $targetStateMap = [];
        foreach ($orderItems as $i => $orderItem) {
            $stateId = $orderItem->getState()->getName();
            $sourceStateBuffer[$orderItem->getIdSalesOrderItem()] = $stateId;
            $process = $this->builder->createProcess($orderItem->getProcess()->getName());
            $sourceState = $process->getStateFromAllProcesses($stateId);

            $log->addSourceState($orderItem, $sourceState->getName());

            $transitions = $stateToTransitionsMap[$orderItem->getState()->getName()];

            $targetState = $sourceState;
            if (count($transitions) > 0) {
                $targetState = $this->checkCondition($transitions, $orderItem, $sourceState, $log);
            }

            $log->addTargetState($orderItem, $targetState->getName());

            $targetStateMap[$i] = $targetState->getName();
        }

        foreach ($orderItems as $i => $orderItem) {
            $this->setState($orderItems[$i], $targetStateMap[$i]);
        }

        return $sourceStateBuffer;
    }

    /**
     * @param SpySalesOrderItem $orderItem
     * @param string $stateName
     */
    protected function setState($orderItem, $stateName)
    {
        if (isset($this->states[$stateName])) {
            $state = $this->states[$stateName];
        } else {
            $state = SpyOmsOrderItemStateQuery::create()->findOneByName($stateName);
            if (!isset($state)) {
                $state = new SpyOmsOrderItemState();
                $state->setName($stateName);
                $state->save();
            }
            $this->states[$stateName] = $state;
        }
        $orderItem->setState($state);
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param ProcessInterface[] $processes
     * @param array $sourceStateBuffer
     *
     * @throws LogicException
     *
     * @return array
     */
    protected function filterItemsWithOnEnterEvent(array $orderItems, array $processes, array $sourceStateBuffer)
    {
        $orderItemsWithOnEnterEvent = [];
        foreach ($orderItems as $orderItem) {
            $stateId = $orderItem->getState()->getName();
            $processId = $orderItem->getProcess()->getName();

            if (!isset($processes[$processId])) {
                throw new LogicException("Unknown process $processId");
            }

            $process = $processes[$processId];
            $targetState = $process->getStateFromAllProcesses($stateId);

            if (isset($sourceStateBuffer[$orderItem->getIdSalesOrderItem()])) {
                $sourceState = $sourceStateBuffer[$orderItem->getIdSalesOrderItem()];
            } else {
                $sourceState = $process->getStateFromAllProcesses($orderItem->getState()->getName());
            }

            if ($sourceState !== $targetState->getName()
                && $targetState->hasOnEnterEvent()
            ) {
                $event = $targetState->getOnEnterEvent();
                if (false === array_key_exists($event->getName(), $orderItemsWithOnEnterEvent)) {
                    $orderItemsWithOnEnterEvent[$event->getName()] = [];
                }
                $orderItemsWithOnEnterEvent[$event->getName()][] = $orderItem;
            }
        }

        return $orderItemsWithOnEnterEvent;
    }

    /**
     * @param array $data
     *
     * @return ReadOnlyArrayObject
     */
    protected function makeDataReadOnly($data)
    {
        if (is_array($data)) {
            $data = new ReadOnlyArrayObject($data);
        }

        return $data;
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
        if (false === array_key_exists($eventId, $this->eventCounter)) {
            $this->eventCounter[$eventId] = 0;
        }
        $this->eventCounter[$eventId]++;

        return $this->eventCounter[$eventId] < self::MAX_EVENT_REPEATS;
    }

    /**
     * @param array $orderItemsWithOnEnterEvent
     * @param ReadOnlyArrayObject $data
     *
     * @return void
     */
    protected function triggerOnEnterEvents(array $orderItemsWithOnEnterEvent, ReadOnlyArrayObject $data)
    {
        if (count($orderItemsWithOnEnterEvent) > 0) {
            foreach ($orderItemsWithOnEnterEvent as $eventId => $orderItems) {
                $this->triggerEvent($eventId, $orderItems, $data);
            }
        }
    }

    /**
     * @param TransitionInterface[] $transitions
     *
     * @return array
     */
    protected function createStateToTransitionMap(array $transitions)
    {
        $stateToTransitionsMap = [];
        foreach ($transitions as $transition) {
            $sourceId = $transition->getSource()->getName();
            if (false === array_key_exists($sourceId, $stateToTransitionsMap)) {
                $stateToTransitionsMap[$sourceId] = [];
            }
            $stateToTransitionsMap[$sourceId][] = $transition;
        }

        return $stateToTransitionsMap;
    }

    /**
     * @param array $states
     * @param ProcessInterface $process
     *
     * @return SpySalesOrderItem[]
     */
    protected function getOrderItemsByState(array $states, ProcessInterface $process)
    {
        return $this->queryContainer
            ->querySalesOrderItemsByState($states, $process->getName())
            ->find()
            ->getData();
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param TransitionLogInterface $log
     * @param ProcessInterface[] $processes
     * @param array $sourceStateBuffer
     *
     * @return void
     */
    protected function saveOrderItems(array $orderItems, TransitionLogInterface $log, array $processes, array $sourceStateBuffer)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $currentTime = new DateTime('now');

        $timeoutModel = clone $this->timeout;

        foreach ($orderItems as $orderItem) {
            $process = $processes[$orderItem->getProcess()->getName()];

            $sourceState = $sourceStateBuffer[$orderItem->getIdSalesOrderItem()];
            $targetState = $orderItem->getState()->getName();

            if ($sourceState !== $targetState) {
                $timeoutModel->dropOldTimeout($process, $sourceState, $orderItem);
                $timeoutModel->setNewTimeout($process, $orderItem, $currentTime);
            }

            if ($orderItem->isModified()) {
                Log::log($orderItem->toArray(), 'orderitems.log');
                $orderItem->save();
                $log->save($orderItem);
            }
        }

        $connection->commit();
    }

    /**
     * @param string $commandString
     *
     * @throws LogicException
     *
     * @return CommandByOrderInterface|CommandByItemInterface
     */
    protected function getCommand($commandString)
    {
        if (!isset($this->commands[$commandString])) {
            throw new LogicException('Command ' . $commandString . ' not found in Settings');
        }

        return $this->commands[$commandString];
    }

    /**
     * @param string $conditionString
     *
     * @return ConditionInterface
     */
    protected function getCondition($conditionString)
    {
        if (!isset($this->conditions[$conditionString])) {
            throw new LogicException('Condition ' . $conditionString . ' not found in Settings');
        }

        return $this->conditions[$conditionString];
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     *
     * @return TransitionLogInterface
     */
    protected function initTransitionLog(array $orderItems)
    {
        $log = clone $this->transitionLog;

        $log->init($orderItems);

        return $log;
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param TransitionLogInterface $log
     *
     * @return void
     */
    protected function logSourceState($orderItems, TransitionLogInterface $log)
    {
        foreach ($orderItems as $orderItem) {
            $stateName = $orderItem->getState()->getName();
            $log->addSourceState($orderItem, $stateName);
        }
    }

}
