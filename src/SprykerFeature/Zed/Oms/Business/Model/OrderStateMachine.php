<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByItemInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use SprykerFeature\Zed\Oms\Business\Model\OrderStateMachine\TimeoutInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\TransitionInterface;
use SprykerFeature\Zed\Oms\Business\Model\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Business\Model\Util\TransitionLogInterface;
use SprykerFeature\Zed\Oms\Business\Model\Util\CollectionToArrayTransformerInterface;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatusQuery;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemStatus;
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

    protected $eventCounter = array();

    protected $returnData = array();

    protected $processBuffer = array();

    protected $statuses = array();

    /**
     * @var OmsQueryContainer
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
     * @var CollectionToArrayTransformerInterface
     */
    protected $collectionToArrayTransformer;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @param OmsQueryContainer $queryContainer
     * @param BuilderInterface $builder
     * @param TransitionLogInterface $transitionLog
     * @param TimeoutInterface $timeout
     * @param CollectionToArrayTransformerInterface $collectionToArrayTransformer
     * @param ReadOnlyArrayObject $activeProcesses
     * @param array $conditions
     * @param array $commands
     * @param FactoryInterface $factory
     */
    public function __construct(
        OmsQueryContainer $queryContainer,
        BuilderInterface $builder,
        TransitionLogInterface $transitionLog,
        TimeoutInterface $timeout,
        CollectionToArrayTransformerInterface $collectionToArrayTransformer,
        ReadOnlyArrayObject $activeProcesses,
        array $conditions,
        array $commands,
        FactoryInterface $factory
    ) {
        $this->queryContainer = $queryContainer;
        $this->builder = $builder;
        $this->transitionLog = $transitionLog;
        $this->timeout = $timeout;
        $this->collectionToArrayTransformer = $collectionToArrayTransformer;
        $this->activeProcesses = $activeProcesses;
        $this->conditions = $conditions;
        $this->commands = $commands;
        $this->factory = $factory;
    }

    /**
     * @param string $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param array $data
     * @param array $logContext
     *
     * @return array
     */
    public function triggerEvent($eventId, array $orderItems, $data, array $logContext = array())
    {
        assert('is_string($eventId)');
        assert('count($orderItems) > 0');

        if (false === $this->checkForEventRepetitions($eventId)) {
            return array();
        }

        $data = $this->makeDataReadOnly($data);

        $processes = $this->getProcesses($orderItems);

        $orderItems = $this->filterAffectedOrderItems($eventId, $orderItems, $processes);

        $log = clone $this->transitionLog;

        $log->addItems($orderItems);

        $orderGroup = $this->groupByOrderAndStatus($eventId, $orderItems, $processes);
        $sourceStatusBuffer = array();
        foreach ($orderGroup as $groupedOrderItems) {
            $this->runCommand($eventId, $groupedOrderItems, $processes, $data, $log);
            $sourceStatusBuffer = $this->updateStatusByEvent($eventId, $groupedOrderItems, $sourceStatusBuffer, $log);
            $this->saveOrderItems($groupedOrderItems, $log, $processes, $sourceStatusBuffer);
        }

        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent($orderItems, $processes, $sourceStatusBuffer);

        $log->saveAll();

        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent, $data, $logContext);

        return $this->returnData;
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param array $data
     * @param array $logContext
     * @return array
     */
    public function triggerEventForNewItem(array $orderItems, array $data, array $logContext = array())
    {
        $data = $this->makeDataReadOnly($data);
        $sourceStatusBuffer = array();
        $processes = $this->getProcesses($orderItems);
        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent($orderItems, $processes, $sourceStatusBuffer);
        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent, $data, $logContext);

        return $this->returnData;
    }

    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkConditions(array $logContext = array())
    {
        $affectedOrderItems = 0;
        foreach ($this->activeProcesses as $processName) {
            $process = $this->builder->createProcess($processName);
            $orderStateMachine = clone $this;
            $affectedOrderItems += $orderStateMachine->checkConditionsForProcess($process, $logContext);
        }

        return $affectedOrderItems;
    }

    /**
     * @param ProcessInterface $process
     * @param array $logContext
     *
     * @return int
     */
    protected function checkConditionsForProcess(ProcessInterface $process, array $logContext = null)
    {
        $transitions = $process->getAllTransitionsWithoutEvent();

        $stateToTransitionsMap = $this->createStateToTransitionMap($transitions);

        $orderItems = $this->getOrderItemsByStatus(array_keys($stateToTransitionsMap), $process);

        $countAffectedItems = count($orderItems);

        if (count($orderItems) === 0) {
            return 0;
        }

        $log = clone $this->transitionLog;

        $log->addItems($orderItems);

        $sourceStatusBuffer = $this->updateStatusByTransition($stateToTransitionsMap, $orderItems, array(), $log);

        $processes = array($process->getName() => $process);

        $this->saveOrderItems($orderItems, $log, $processes, $sourceStatusBuffer);

        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent($orderItems, $processes, $sourceStatusBuffer);

        $data = $this->makeDataReadOnly(array());

        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent, $data);

        return $countAffectedItems;
    }

    /**
     * @param TransitionInterface[] $transitions
     * @param SpySalesOrderItem $orderItem
     * @param StatusInterface $sourceStatus
     * @param TransitionLogInterface $log
     *
     * @return StatusInterface
     * @throws Exception
     */
    protected function checkCondition(array $transitions, $orderItem, StatusInterface $sourceStatus, TransitionLogInterface $log)
    {
        $possibleTransitions = array();

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

                if (true === $conditionCheck) {
                    $log->addCondition($orderItem, $conditionModel);
                    array_unshift($possibleTransitions, $transition);
                }
            } else {
                array_push($possibleTransitions, $transition);
            }
        }

        if (count($possibleTransitions) > 0) {
            $selectedTransition = array_shift($possibleTransitions);
            $targetStatus = $selectedTransition->getTarget();
        } else {
            $targetStatus = $sourceStatus;
        }

        return $targetStatus;
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @return ProcessInterface[]
     */
    protected function getProcesses(array $orderItems)
    {
        $processes = array();
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
     * @param $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param ProcessInterface[] $processes
     *
     * @return SpySalesOrderItem[]
     */
    protected function filterAffectedOrderItems($eventId, array $orderItems, $processes)
    {
        $orderItemsFiltered = array();
        foreach ($orderItems as $orderItem) {
            $statusId = $orderItem->getStatus()->getName();
            $processId = $orderItem->getProcess()->getName();
            $process = $processes[$processId];

            $status = $process->getStatusFromAllProcesses($statusId);

            if ($status->hasEvent($eventId)) {
                $orderItemsFiltered[] = $orderItem;
            }
        }

        return $orderItemsFiltered;
    }

    /**
     * @param $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param Process[] $processes
     *
     * @return array
     */
    protected function groupByOrderAndStatus($eventId, array $orderItems, $processes)
    {
        $orderEventGroup = array();
        foreach ($orderItems as $orderItem) {
            $statusId = $orderItem->getStatus()->getName();
            $processId = $orderItem->getProcess()->getName();
            $process = $processes[$processId];
            $orderId = $orderItem->getOrder()->getIdSalesOrder();

            $status = $process->getStatusFromAllProcesses($statusId);

            if ($status->hasEvent($eventId)) {
                $key = $orderId . '-' . $statusId;
                if (!isset($orderEventGroup[$key])) {
                    $orderEventGroup[$key] = array();
                }
                $orderEventGroup[$key][] = $orderItem;
            }
        }

        return $orderEventGroup;
    }

    /**
     * @param CommandInterface $command
     * @return string
     *
     * @throws LogicException
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
     * @param $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param ProcessInterface[] $processes
     * @param ReadOnlyArrayObject $data
     * @param TransitionLogInterface $log
     *
     * @throws Exception
     */
    protected function runCommand($eventId, array $orderItems, array $processes, ReadOnlyArrayObject $data, TransitionLogInterface $log)
    {
        $orderEntity = current($orderItems)->getOrder();
        foreach ($orderItems as $orderItem) {
            $statusId = $orderItem->getStatus()->getName();
            $processId = $orderItem->getProcess()->getName();
            $process = $processes[$processId];
            $status = $process->getStatusFromAllProcesses($statusId);
            $event = $status->getEvent($eventId);

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
     * @param $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param array $sourceStatusBuffer
     * @param TransitionLogInterface $log
     *
     * @return array
     */
    protected function updateStatusByEvent($eventId, array $orderItems, array $sourceStatusBuffer, TransitionLogInterface $log)
    {
        assert(is_string($eventId) || is_null($eventId));
        if (is_null($sourceStatusBuffer)) {
            $sourceStatusBuffer = array();
        }

        $targetStatusMap = array();
        foreach ($orderItems as $i => $orderItem) {
            $statusId = $orderItem->getStatus()->getName();
            $sourceStatusBuffer[$orderItem->getIdSalesOrderItem()] = $statusId;

            $process = $this->builder->createProcess($orderItem->getProcess()->getName());
            $sourceStatus = $process->getStatusFromAllProcesses($statusId);

            $log->addSourceStatus($orderItem, $sourceStatus);

            $targetStatus = $sourceStatus;
            if (isset($eventId) && $sourceStatus->hasEvent($eventId)) {
                $transitions = $sourceStatus->getEvent($eventId)->getTransitionsBySource($sourceStatus);
                $targetStatus = $this->checkCondition($transitions, $orderItem, $sourceStatus, $log);
                $log->addTargetStatus($orderItem, $targetStatus);
            }

            $targetStatusMap[$i] = $targetStatus->getName();
        }

        foreach ($orderItems as $i => $orderItem) {
            $this->setStatus($orderItems[$i], $targetStatusMap[$i]);
        }

        return $sourceStatusBuffer;
    }

    /**
     * @param array $stateToTransitionsMap
     * @param SpySalesOrderItem[] $orderItems
     * @param array $sourceStatusBuffer
     * @param TransitionLogInterface $log
     *
     * @return array
     */
    protected function updateStatusByTransition($stateToTransitionsMap, array $orderItems, array $sourceStatusBuffer, TransitionLogInterface $log)
    {
        if (is_null($sourceStatusBuffer)) {
            $sourceStatusBuffer = array();
        }
        $targetStatusMap = array();
        foreach ($orderItems as $i => $orderItem) {
            $statusId = $orderItem->getStatus()->getName();
            $sourceStatusBuffer[$orderItem->getIdSalesOrderItem()] = $statusId;
            $process = $this->builder->createProcess($orderItem->getProcess()->getName());
            $sourceStatus = $process->getStatusFromAllProcesses($statusId);

            $log->addSourceStatus($orderItem, $sourceStatus);

            $transitions = $stateToTransitionsMap[$orderItem->getStatus()->getName()];

            $targetStatus = $sourceStatus;
            if (count($transitions) > 0) {
                $targetStatus = $this->checkCondition($transitions, $orderItem, $sourceStatus, $log);
            }

            $log->addTargetStatus($orderItem, $targetStatus);

            $targetStatusMap[$i] = $targetStatus->getName();
        }

        foreach ($orderItems as $i => $orderItem) {
            $this->setStatus($orderItems[$i], $targetStatusMap[$i]);
        }

        return $sourceStatusBuffer;
    }

    /**
     * @param SpySalesOrderItem $orderItem
     * @param $statusName
     */
    protected function setStatus($orderItem, $statusName)
    {
        if (isset($this->statuses[$statusName])) {
            $status = $this->statuses[$statusName];
        } else {
            $status = SpyOmsOrderItemStatusQuery::create()->findOneByName($statusName);
            if (!isset($status)) {
                $status = new SpyOmsOrderItemStatus();
                $status->setName($statusName);
                $status->save();
            }
            $this->statuses[$statusName] = $status;
        }
        $orderItem->setStatus($status);
    }

    /**
     * @param array $orderItems
     * @param $processes
     * @param array $sourceStatusBuffer
     *
     * @return array
     * @throws LogicException
     */
    protected function filterItemsWithOnEnterEvent(array $orderItems, $processes, array $sourceStatusBuffer)
    {
        $orderItemsWithOnEnterEvent = array();
        foreach ($orderItems as $orderItem) {
            $statusId = $orderItem->getStatus()->getName();
            $processId = $orderItem->getProcess()->getName();

            if (!isset($processes[$processId])) {
                throw new LogicException("Unknown process $processId");
            }

            $process = $processes[$processId];
            $targetStatus = $process->getStatusFromAllProcesses($statusId);

            if (isset($sourceStatusBuffer[$orderItem->getIdSalesOrderItem()])) {
                $sourceStatus = $sourceStatusBuffer[$orderItem->getIdSalesOrderItem()];
            } else {
                $sourceStatus = $process->getStatusFromAllProcesses($orderItem->getStatus()->getName());
            }

            if ($sourceStatus !== $targetStatus->getName()
                && $targetStatus->hasOnEnterEvent()
            ) {
                $event = $targetStatus->getOnEnterEvent();
                if (false === array_key_exists($event->getName(), $orderItemsWithOnEnterEvent)) {
                    $orderItemsWithOnEnterEvent[$event->getName()] = array();
                }
                $orderItemsWithOnEnterEvent[$event->getName()][] = $orderItem;
            }
        }

        return $orderItemsWithOnEnterEvent;
    }

    /**
     * @param $data
     *
     * @return ReadOnlyArrayObject
     */
    protected function makeDataReadOnly($data)
    {
        if (is_array($data)) {
            $data = $this->factory->createModelUtilReadOnlyArrayObject($data);

            return $data;
        }

        return $data;
    }

    /**
     * To protect of loops, every event can only be used some times
     *
     * @param $eventId
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
     * @param $orderItemsWithOnEnterEvent
     * @param ReadOnlyArrayObject $data
     */
    protected function triggerOnEnterEvents($orderItemsWithOnEnterEvent, ReadOnlyArrayObject $data)
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
        $stateToTransitionsMap = array();
        foreach ($transitions as $transition) {
            $sourceId = $transition->getSource()->getName();
            if (false === array_key_exists($sourceId, $stateToTransitionsMap)) {
                $stateToTransitionsMap[$sourceId] = array();
            }
            $stateToTransitionsMap[$sourceId][] = $transition;
        }

        return $stateToTransitionsMap;
    }

    /**
     * @param $states
     * @param ProcessInterface $process
     *
     * @return SpySalesOrderItem[]
     */
    protected function getOrderItemsByStatus($states, ProcessInterface $process)
    {
        $orderItems = $this->queryContainer->getOrderItemsByStatus($states, $process)->find();

        return $this->collectionToArrayTransformer->transformCollectionToArray($orderItems);
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param TransitionLogInterface $log
     * @param ProcessInterface[] $processes
     * @param array $sourceStatusBuffer
     */
    protected function saveOrderItems(array $orderItems, TransitionLogInterface $log, array $processes, array $sourceStatusBuffer)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $currentTime = new DateTime('now');

        $timeoutModel = clone $this->timeout;

        foreach ($orderItems as $orderItem) {
            $process = $processes[$orderItem->getProcess()->getName()];

            $sourceStatus = $sourceStatusBuffer[$orderItem->getIdSalesOrderItem()];
            $targetStatus = $orderItem->getStatus()->getName();

            if ($sourceStatus != $targetStatus) {
                $timeoutModel->dropOldTimeout($process, $sourceStatus, $orderItem);
                $timeoutModel->setNewTimeout($process, $orderItem, $currentTime);
            }

            if ($orderItem->isModified()) {
                $orderItem->save();
                $log->save($orderItem);
            }
        }

        $connection->commit();
    }

    /**
     * @param $commandString
     *
     * @return CommandByOrderInterface|CommandByItemInterface
     * @throws LogicException
     */
    protected function getCommand($commandString)
    {
        if (!isset($this->commands[$commandString])) {
            throw new LogicException('Command ' . $commandString . ' not found in Settings');
        }

        return $this->commands[$commandString];
    }

    /**
     * @param $conditionString
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

}
