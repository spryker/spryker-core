<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use DateTime;
use Exception;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
use Generated\Shared\Transfer\OmsEventTriggerResponseTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use LogicException;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\Oms\Business\Notifier\EventTriggeredNotifierInterface;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Process\StateInterface;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Business\Util\ReservationInterface;
use Spryker\Zed\Oms\Business\Util\TransitionLogInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByItemInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class OrderStateMachine implements OrderStateMachineInterface
{
    use DatabaseTransactionHandlerTrait;
    use ActiveRecordBatchProcessorTrait;

    /**
     * @var string
     */
    public const BY_ITEM = 'byItem';

    /**
     * @var string
     */
    public const BY_ORDER = 'byOrder';

    /**
     * @var int
     */
    public const MAX_EVENT_REPEATS = 10;

    /**
     * @deprecated Not in use anymore, will be removed in the next major.
     *
     * @var int
     */
    public const MAX_ON_ENTER = 50;

    /**
     * @var string
     */
    protected const RETURN_DATA_UPDATED_ORDER_ITEMS = 'updatedOrderItems';

    /**
     * @var array
     */
    protected $eventCounter = [];

    /**
     * @var array
     */
    protected $returnData = [];

    /**
     * @var array
     */
    protected $processBuffer = [];

    /**
     * @var array
     */
    protected $states = [];

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface
     */
    protected $timeout;

    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @var \Spryker\Zed\Oms\Business\Util\TransitionLogInterface
     */
    protected $transitionLog;

    /**
     * @var \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject
     */
    protected $activeProcesses;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface
     */
    protected $conditions;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface
     */
    protected $commands;

    /**
     * @var \Spryker\Zed\Oms\Business\Util\ReservationInterface
     */
    protected $reservation;

    /**
     * @var \Spryker\Zed\Oms\OmsConfig
     */
    protected $omsConfig;

    /**
     * @var \Spryker\Zed\Oms\Business\Notifier\EventTriggeredNotifierInterface
     */
    protected EventTriggeredNotifierInterface $eventTriggeredNotifier;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $transitionLog
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface $timeout
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $activeProcesses
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface|array $conditions
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface|array $commands
     * @param \Spryker\Zed\Oms\Business\Util\ReservationInterface $reservation
     * @param \Spryker\Zed\Oms\OmsConfig $omsConfig
     * @param \Spryker\Zed\Oms\Business\Notifier\EventTriggeredNotifierInterface $eventTriggeredNotifier
     */
    public function __construct(
        OmsQueryContainerInterface $queryContainer,
        BuilderInterface $builder,
        TransitionLogInterface $transitionLog,
        TimeoutInterface $timeout,
        ReadOnlyArrayObject $activeProcesses,
        $conditions,
        $commands,
        ReservationInterface $reservation,
        OmsConfig $omsConfig,
        EventTriggeredNotifierInterface $eventTriggeredNotifier
    ) {
        $this->queryContainer = $queryContainer;
        $this->builder = $builder;
        $this->transitionLog = $transitionLog;
        $this->timeout = $timeout;
        $this->activeProcesses = $activeProcesses;
        $this->setConditions($conditions);
        $this->setCommands($commands);
        $this->reservation = $reservation;
        $this->omsConfig = $omsConfig;
        $this->eventTriggeredNotifier = $eventTriggeredNotifier;
    }

    /**
     * Converts array to collection for BC
     *
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface|array $conditions
     *
     * @return void
     */
    protected function setConditions($conditions)
    {
        if ($conditions instanceof ConditionCollectionInterface) {
            $this->conditions = $conditions;

            return;
        }

        $conditionCollection = new ConditionCollection();
        foreach ($conditions as $name => $condition) {
            $conditionCollection->add($condition, $name);
        }

        $this->conditions = $conditionCollection;
    }

    /**
     * Converts array to collection for BC
     *
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface|array $commands
     *
     * @return void
     */
    protected function setCommands($commands)
    {
        if ($commands instanceof CommandCollectionInterface) {
            $this->commands = $commands;

            return;
        }

        $commandCollection = new CommandCollection();
        foreach ($commands as $name => $command) {
            $commandCollection->add($command, $name);
        }

        $this->commands = $commandCollection;
    }

    /**
     * @param string $eventId
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject|array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, array $orderItems, $data)
    {
        $data = $this->makeDataReadOnly($data);

        $processes = $this->getProcesses($orderItems);

        $orderItems = $this->filterAffectedOrderItems($eventId, $orderItems, $processes);

        $log = $this->initTransitionLog($orderItems);

        $orderGroup = $this->groupByOrderAndState($eventId, $orderItems, $processes);
        $sourceStateBuffer = [];

        $allProcessedOrderItems = [];
        foreach ($orderGroup as $orderGroupKey => $groupedOrderItems) {
            if (!$this->checkOrderGroupForEventRepetitions($eventId, $orderGroupKey)) {
                continue;
            }

            $this->logSourceState($groupedOrderItems, $log);

            $processedOrderItems = $this->runCommand($eventId, $groupedOrderItems, $processes, $data, $log);
            if ($processedOrderItems === null) {
                continue;
            }
            $sourceStateBuffer = $this->updateStateByEvent($eventId, $processedOrderItems, $sourceStateBuffer, $log);
            $this->saveOrderItems($processedOrderItems, $log, $processes, $sourceStateBuffer);

            $currentOrderItemEntity = current($processedOrderItems);

            if ($currentOrderItemEntity) {
                $orderEntity = $currentOrderItemEntity->getOrder();

                $this->eventTriggeredNotifier->notifyOmsEventTriggeredListeners($eventId, $processedOrderItems, $orderEntity, $data);
            }

            $allProcessedOrderItems = array_merge($allProcessedOrderItems, $processedOrderItems);
        }

        $orderItemsWithOnEnterEvent = $this->filterItemsWithOnEnterEvent($allProcessedOrderItems, $processes, $sourceStateBuffer);

        $log->saveAll();

        $this->triggerOnEnterEvents($orderItemsWithOnEnterEvent, $data);

        return $this->returnData;
    }

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array<string, mixed> $data
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
     * @param array<string, mixed> $data
     *
     * @return array|null
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
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<string, mixed> $data
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

        $orderItemsWithTimeoutEvent = $this->filterItemsWithTimeoutEvent($orderItems, $processes);
        $this->saveTimeoutEvents($orderItemsWithTimeoutEvent);

        return $this->returnData;
    }

    /**
     * @param array<int> $orderItemIds
     * @param array<string, mixed> $data
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
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     *
     * @return int
     */
    public function checkConditions(array $logContext = [], ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer = null)
    {
        $affectedOrderItems = 0;
        foreach ($this->activeProcesses as $processName) {
            $process = $this->builder->createProcess($processName);
            $orderStateMachine = clone $this;
            $affectedOrderItems += $orderStateMachine->checkConditionsForProcess($process, $omsCheckConditionsQueryCriteriaTransfer);
        }

        return $affectedOrderItems;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     *
     * @return int
     */
    protected function checkConditionsForProcess(ProcessInterface $process, ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer)
    {
        $transitions = $process->getAllTransitionsWithoutEvent();

        $stateToTransitionsMap = $this->createStateToTransitionMap($transitions);

        $orderItems = $this->getOrderItemsByState(array_keys($stateToTransitionsMap), $process, $omsCheckConditionsQueryCriteriaTransfer);

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
     * @param array<\Spryker\Zed\Oms\Business\Process\TransitionInterface> $transitions
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $sourceState
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $log
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
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
                    $log->setIsError(true);
                    $log->setErrorMessage(get_class($e) . ' - ' . $e->getMessage());
                    $log->saveAll();

                    throw $e;
                }

                if ($conditionCheck === true) {
                    array_unshift($possibleTransitions, $transition);
                }

                $log->addCondition($orderItem, $conditionModel);
            } else {
                array_push($possibleTransitions, $transition);
            }
        }

        if (count($possibleTransitions) > 0) {
            /** @var \Spryker\Zed\Oms\Business\Process\TransitionInterface $selectedTransition */
            $selectedTransition = array_shift($possibleTransitions);
            $targetState = $selectedTransition->getTarget();
        } else {
            $targetState = $sourceState;
        }

        return $targetState;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return array<\Spryker\Zed\Oms\Business\Process\ProcessInterface>
     */
    protected function getProcesses(array $orderItems)
    {
        $processes = [];
        foreach ($orderItems as $orderItem) {
            $processName = $orderItem->getProcess()->getName();
            if (array_key_exists($processName, $processes) === false) {
                $processes[$processName] = $this->builder->createProcess($processName);
            }
        }

        return $processes;
    }

    /**
     * Filters out all items that are not affected by the current event
     *
     * @param string $eventId
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
     *
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    protected function filterAffectedOrderItems($eventId, array $orderItems, $processes)
    {
        $orderItemsFiltered = [];
        foreach ($orderItems as $orderItem) {
            $stateName = $orderItem->getState()->getName();
            $processName = $orderItem->getProcess()->getName();
            $process = $processes[$processName];

            $state = $process->getStateFromAllProcesses($stateName);

            if ($state->hasEvent($eventId)) {
                $orderItemsFiltered[] = $orderItem;
            }
        }

        return $orderItemsFiltered;
    }

    /**
     * @param string $eventId
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
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
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface $command
     *
     * @throws \LogicException
     *
     * @return string
     */
    protected function getCommandType(CommandInterface $command)
    {
        if ($command instanceof CommandByOrderInterface) {
            return static::BY_ORDER;
        }
        if ($command instanceof CommandByItemInterface) {
            return static::BY_ITEM;
        }

        throw new LogicException('Unknown type of command: ' . get_class($command));
    }

    /**
     * Specification:
     * - Performs commands on items
     * - All passing items should have the same event available
     * - For CommandByOrderInterface the command will be taken from the first order item
     *
     * @param string $eventId
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $log
     *
     * @throws \LogicException
     *
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>|null
     */
    protected function runCommand($eventId, array $orderItems, array $processes, ReadOnlyArrayObject $data, TransitionLogInterface $log)
    {
        $processedOrderItems = [];

        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem $currentOrderItemEntity */
        $currentOrderItemEntity = current($orderItems);
        $orderEntity = $currentOrderItemEntity->getOrder();

        foreach ($orderItems as $orderItemEntity) {
            $stateId = $orderItemEntity->getState()->getName();
            $processId = $orderItemEntity->getProcess()->getName();
            $process = $processes[$processId];
            $state = $process->getStateFromAllProcesses($stateId);
            $event = $state->getEvent($eventId);

            $log->setEvent($event);

            if (!$event->hasCommand()) {
                $processedOrderItems[] = $orderItemEntity;

                continue;
            }

            /** @var \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface|\Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByItemInterface|\Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface $command */
            $command = $this->getCommand($event->getCommand());
            $type = $this->getCommandType($command);

            $log->addCommand($orderItemEntity, $command);

            $omsEventTriggerResponseTransfer = (new OmsEventTriggerResponseTransfer())->setIsSuccessful(true);
            $this->returnData[OmsConfig::OMS_EVENT_TRIGGER_RESPONSE] = $omsEventTriggerResponseTransfer;

            try {
                if ($command instanceof CommandByOrderInterface) {
                    $returnData = $command->run($orderItems, $orderEntity, $data);
                    if (is_array($returnData)) {
                        $this->returnData = array_merge($this->returnData, $returnData);
                        $orderItems = $this->handleUpdatedOrderItems($orderItems, $returnData, $log);
                    }

                    return $orderItems;
                }

                if ($command instanceof CommandByItemInterface) {
                    $returnData = $command->run($orderItemEntity, $data);
                    $this->returnData = array_merge($this->returnData, $returnData);
                    $processedOrderItems[] = $orderItemEntity;
                } else {
                    throw new LogicException('Unknown type of command: ' . get_class($command));
                }
            } catch (Exception $e) {
                $log->setIsError(true);
                $log->setErrorMessage(get_class($e) . ' - ' . $e->getMessage());
                $log->saveAll();

                ErrorLogger::getInstance()->log($e);

                $errorMessage = $e->getMessage() ?: 'Currently not executable.';
                $omsEventTriggerResponseTransfer
                    ->setIsSuccessful(false)
                    ->addMessage(
                        (new MessageTransfer())->setValue($errorMessage),
                    );
                $this->returnData[OmsConfig::OMS_EVENT_TRIGGER_RESPONSE] = $omsEventTriggerResponseTransfer;

                if ($type === static::BY_ORDER) {
                    return null; // intercept the processing of a grouped order items for the current order state
                }
            }
        }

        return $processedOrderItems;
    }

    /**
     * @param string $eventId
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array $sourceStateBuffer
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $log
     *
     * @return array
     */
    protected function updateStateByEvent($eventId, array $orderItems, array $sourceStateBuffer, TransitionLogInterface $log)
    {
        $targetStateMap = [];
        foreach ($orderItems as $i => $orderItem) {
            $stateId = $orderItem->getState()->getName();
            $sourceStateBuffer[$orderItem->getIdSalesOrderItem()] = $stateId;

            $process = $this->builder->createProcess($orderItem->getProcess()->getName());
            $sourceState = $process->getStateFromAllProcesses($stateId);

            $log->addSourceState($orderItem, $sourceState->getName());

            $targetState = $sourceState;
            if ($eventId && $sourceState->hasEvent($eventId)) {
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
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array $sourceStateBuffer
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $log
     *
     * @return array
     */
    protected function updateStateByTransition($stateToTransitionsMap, array $orderItems, array $sourceStateBuffer, TransitionLogInterface $log)
    {
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
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param string $stateName
     *
     * @return void
     */
    protected function setState($orderItem, $stateName)
    {
        if (isset($this->states[$stateName])) {
            $state = $this->states[$stateName];
        } else {
            $state = SpyOmsOrderItemStateQuery::create()->findOneByName($stateName);
            if ($state === null) {
                $state = new SpyOmsOrderItemState();
                $state->setName($stateName);
                $state->save();
            }
            $this->states[$stateName] = $state;
        }
        $orderItem->setState($state);
        $orderItem->setLastStateChange(new DateTime());
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
     * @param array $sourceStateBuffer
     *
     * @throws \LogicException
     *
     * @return array<array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>>
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

            if ($sourceState === $targetState && $targetState->isReserved()) {
                $reservationRequestTransfer = (new ReservationRequestTransfer())
                    ->fromArray($orderItem->toArray(), true);
                $this->reservation->updateReservation($reservationRequestTransfer);
            }

            if (
                $sourceState !== $targetState->getName()
                && $targetState->hasOnEnterEvent()
            ) {
                $event = $targetState->getOnEnterEvent();
                if (array_key_exists($event->getName(), $orderItemsWithOnEnterEvent) === false) {
                    $orderItemsWithOnEnterEvent[$event->getName()] = [];
                }
                $orderItemsWithOnEnterEvent[$event->getName()][] = $orderItem;
            }
        }

        return $orderItemsWithOnEnterEvent;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject|array $data
     *
     * @return \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject
     */
    protected function makeDataReadOnly($data)
    {
        if (is_array($data)) {
            $data = new ReadOnlyArrayObject($data);
        }

        return $data;
    }

    /**
     * To protect against loops, every event can only be used several times per order group.
     *
     * @param string $eventId
     * @param string $orderGroupKey
     *
     * @return bool
     */
    protected function checkOrderGroupForEventRepetitions(string $eventId, string $orderGroupKey): bool
    {
        if (!isset($this->eventCounter[$eventId][$orderGroupKey])) {
            $this->eventCounter[$eventId][$orderGroupKey] = 0;
        }

        $this->eventCounter[$eventId][$orderGroupKey]++;

        return $this->eventCounter[$eventId][$orderGroupKey] < static::MAX_EVENT_REPEATS;
    }

    /**
     * @param array<array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>> $orderItemsWithOnEnterEvent
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
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
     * @param array<\Spryker\Zed\Oms\Business\Process\TransitionInterface> $transitions
     *
     * @return array
     */
    protected function createStateToTransitionMap(array $transitions)
    {
        $stateToTransitionsMap = [];
        foreach ($transitions as $transition) {
            $sourceId = $transition->getSource()->getName();
            if (array_key_exists($sourceId, $stateToTransitionsMap) === false) {
                $stateToTransitionsMap[$sourceId] = [];
            }
            $stateToTransitionsMap[$sourceId][] = $transition;
        }

        return $stateToTransitionsMap;
    }

    /**
     * @param array $states
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     *
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    protected function getOrderItemsByState(
        array $states,
        ProcessInterface $process,
        ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer
    ) {
        $omsCheckConditionsQueryCriteriaTransfer = $this->prepareOmsCheckConditionsQueryCriteriaTransfer($omsCheckConditionsQueryCriteriaTransfer);

        $storeName = $omsCheckConditionsQueryCriteriaTransfer->getStoreName();
        $limit = $omsCheckConditionsQueryCriteriaTransfer->getLimit();

        if ($storeName === null && $limit === null) {
            return $this->queryContainer
                ->querySalesOrderItemsByState($states, $process->getName())
                ->find()
                ->getData();
        }

        $omsProcessEntity = $this->queryContainer->queryProcess($process->getName())->findOne();
        /** @var \Propel\Runtime\Collection\ObjectCollection $omsOrderItemEntityCollection */
        $omsOrderItemEntityCollection = $this->queryContainer->querySalesOrderItemStatesByName($states)->find();

        if ($omsProcessEntity === null || $omsOrderItemEntityCollection->count() === 0) {
            return [];
        }

        return $this->queryContainer
            ->querySalesOrderItemsByProcessIdStateIdsAndQueryCriteria(
                $omsProcessEntity->getIdOmsOrderProcess(),
                $omsOrderItemEntityCollection->getPrimaryKeys(),
                $omsCheckConditionsQueryCriteriaTransfer,
            )
            ->find()
            ->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer
     */
    protected function prepareOmsCheckConditionsQueryCriteriaTransfer(
        ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer = null
    ): OmsCheckConditionsQueryCriteriaTransfer {
        if ($omsCheckConditionsQueryCriteriaTransfer === null) {
            $omsCheckConditionsQueryCriteriaTransfer = new OmsCheckConditionsQueryCriteriaTransfer();
        }

        if ($omsCheckConditionsQueryCriteriaTransfer->getLimit() === null) {
            $omsCheckConditionsQueryCriteriaTransfer->setLimit($this->omsConfig->getCheckConditionsQueryLimit());
        }

        return $omsCheckConditionsQueryCriteriaTransfer;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $log
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
     * @param array $sourceStateBuffer
     *
     * @return void
     */
    protected function saveOrderItems(array $orderItems, TransitionLogInterface $log, array $processes, array $sourceStateBuffer)
    {
        $currentTime = new DateTime('now');
        $timeoutModel = clone $this->timeout;

        $this->handleDatabaseTransaction(function () use ($orderItems, $processes, $sourceStateBuffer, $timeoutModel, $log, $currentTime) {
            $this->executeBulkSaveOrderItemTransaction(
                $orderItems,
                $processes,
                $sourceStateBuffer,
                $timeoutModel,
                $log,
                $currentTime,
            );
        });
    }

    /**
     * @deprecated Use {@link executeBulkSaveOrderItemTransaction()} instead.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param array $processes
     * @param array $sourceStateBuffer
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface $timeoutModel
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $log
     * @param \DateTime $currentTime
     *
     * @return void
     */
    protected function executeSaveOrderItemTransaction(
        SpySalesOrderItem $orderItem,
        array $processes,
        array $sourceStateBuffer,
        TimeoutInterface $timeoutModel,
        TransitionLogInterface $log,
        DateTime $currentTime
    ) {
        $process = $processes[$orderItem->getProcess()->getName()];

        $sourceState = $sourceStateBuffer[$orderItem->getIdSalesOrderItem()];
        $targetState = $orderItem->getState()->getName();

        if ($sourceState !== $targetState) {
            $timeoutModel->dropOldTimeout($process, $sourceState, $orderItem);
            $timeoutModel->setNewTimeout($process, $orderItem, $currentTime);
        }

        $orderItem->save();
        $this->updateOmsReservation($process, $sourceState, $targetState, $orderItem);
        $log->save($orderItem);
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItemEntities
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
     * @param array<int, string> $sourceStateBuffer
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface $timeoutModel
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $log
     * @param \DateTime $currentTime
     *
     * @return void
     */
    protected function executeBulkSaveOrderItemTransaction(
        array $orderItemEntities,
        array $processes,
        array $sourceStateBuffer,
        TimeoutInterface $timeoutModel,
        TransitionLogInterface $log,
        DateTime $currentTime
    ) {
        $indexedOrderItemEntities = [];
        foreach ($orderItemEntities as $orderItemEntity) {
            $process = $processes[$orderItemEntity->getProcess()->getName()];
            $sourceState = $sourceStateBuffer[$orderItemEntity->getIdSalesOrderItem()];
            $targetState = $orderItemEntity->getState()->getName();

            if ($sourceState !== $targetState) {
                $timeoutModel->dropOldTimeout($process, $sourceState, $orderItemEntity);
                $timeoutModel->setNewTimeout($process, $orderItemEntity, $currentTime);
            }
            $indexedOrderItemEntities[$orderItemEntity->getIdSalesOrderItem()] = $orderItemEntity;
            $this->persist($orderItemEntity);
        }

        $this->commit();

        foreach ($indexedOrderItemEntities as $orderItemEntity) {
            $this->updateOmsReservation($process, $sourceState, $targetState, $orderItemEntity);
            $log->save($orderItemEntity);
        }
    }

    /**
     * @param string $command
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface
     */
    protected function getCommand($command)
    {
        return $this->commands->get($command);
    }

    /**
     * @param string $condition
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface
     */
    protected function getCondition($condition)
    {
        return $this->conditions->get($condition);
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return \Spryker\Zed\Oms\Business\Util\TransitionLogInterface
     */
    protected function initTransitionLog(array $orderItems)
    {
        $log = clone $this->transitionLog;

        $log->init($orderItems);

        return $log;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $log
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

    /**
     * @deprecated Use {@link updateOmsReservation()} instead.
     *
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string $sourceStateId
     * @param string $targetStateId
     * @param string $sku
     *
     * @return void
     */
    protected function updateReservation(ProcessInterface $process, $sourceStateId, $targetStateId, $sku)
    {
        $sourceStateIsReserved = $process->getStateFromAllProcesses($sourceStateId)->isReserved();
        $targetStateIsReserved = $process->getStateFromAllProcesses($targetStateId)->isReserved();

        if ($sourceStateIsReserved !== $targetStateIsReserved) {
            $this->reservation->updateReservationQuantity($sku);
        }
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string $sourceState
     * @param string $targetState
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return void
     */
    protected function updateOmsReservation(
        ProcessInterface $process,
        string $sourceState,
        string $targetState,
        SpySalesOrderItem $salesOrderItem
    ): void {
        $sourceStateIsReserved = $process->getStateFromAllProcesses($sourceState)->isReserved();
        $targetStateIsReserved = $process->getStateFromAllProcesses($targetState)->isReserved();

        if ($sourceStateIsReserved !== $targetStateIsReserved) {
            $reservationRequestTransfer = (new ReservationRequestTransfer())
                ->fromArray($salesOrderItem->toArray(), true);
            $this->reservation->updateReservation($reservationRequestTransfer);
        }
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
     *
     * @throws \LogicException
     *
     * @return array
     */
    protected function filterItemsWithTimeoutEvent(array $orderItems, array $processes)
    {
        $orderItemsWithTimeoutEvent = [];
        foreach ($orderItems as $orderItem) {
            $stateId = $orderItem->getState()->getName();
            $processId = $orderItem->getProcess()->getName();

            if (!isset($processes[$processId])) {
                throw new LogicException("Unknown process $processId");
            }

            $process = $processes[$processId];
            $targetState = $process->getStateFromAllProcesses($stateId);

            if ($targetState->hasTimeoutEvent()) {
                $events = $targetState->getTimeoutEvents();
                foreach ($events as $event) {
                    $orderItemKey = sprintf('%s_%s', $orderItem->getIdSalesOrderItem(), $orderItem->getFkOmsOrderItemState());
                    if (!isset($orderItemsWithTimeoutEvent[$event->getName()][$orderItemKey])) {
                        $orderItemsWithTimeoutEvent[$event->getName()][$orderItemKey] = $orderItem;
                    }
                }
            }
        }

        return $orderItemsWithTimeoutEvent;
    }

    /**
     * @param array $orderItemsWithTimeoutEvent
     *
     * @return void
     */
    protected function saveTimeoutEvents(array $orderItemsWithTimeoutEvent)
    {
        foreach ($orderItemsWithTimeoutEvent as $eventId => $orderItems) {
            $this->saveTimeoutEvent($eventId, $orderItems);
        }
    }

    /**
     * @param string $eventId
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return void
     */
    protected function saveTimeoutEvent($eventId, array $orderItems)
    {
        $processes = $this->getProcesses($orderItems);
        $orderItems = $this->filterAffectedOrderItems($eventId, $orderItems, $processes);
        $sourceStateBuffer = $this->getStateByEvent($orderItems);
        $orderGroup = $this->groupByOrderAndState($eventId, $orderItems, $processes);

        foreach ($orderGroup as $orderGroupKey => $groupedOrderItems) {
            if (!$this->checkOrderGroupForEventRepetitions($eventId, $orderGroupKey)) {
                return;
            }

            $this->saveOrderItemsTimeout($groupedOrderItems, $processes, $sourceStateBuffer);
        }
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return array
     */
    protected function getStateByEvent(array $orderItems)
    {
        $sourceStateBuffer = [];
        foreach ($orderItems as $orderItem) {
            $stateId = $orderItem->getState()->getName();
            $sourceStateBuffer[$orderItem->getIdSalesOrderItem()] = $stateId;
        }

        return $sourceStateBuffer;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
     * @param array $sourceStateBuffer
     *
     * @return void
     */
    protected function saveOrderItemsTimeout(array $orderItems, array $processes, array $sourceStateBuffer)
    {
        $currentTime = new DateTime('now');

        $this->timeout->dropOldTimeouts($orderItems, $processes, $sourceStateBuffer);
        $this->timeout->setNewTimeouts($orderItems, $currentTime, $processes);
    }

    /**
     * @param list<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<mixed> $returnData
     * @param \Spryker\Zed\Oms\Business\Util\TransitionLogInterface $log
     *
     * @return list<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    protected function handleUpdatedOrderItems(array $orderItems, array $returnData, TransitionLogInterface $log): array
    {
        if (!isset($returnData[static::RETURN_DATA_UPDATED_ORDER_ITEMS])) {
            return $orderItems;
        }

        $originalSalesOrderItemIds = array_map(
            static fn (SpySalesOrderItem $orderItem): int => $orderItem->getIdSalesOrderItem(),
            $orderItems,
        );
        $updatedOrderItemIds = array_map(
            static fn (SpySalesOrderItem $orderItem): int => $orderItem->getIdSalesOrderItem(),
            $returnData[static::RETURN_DATA_UPDATED_ORDER_ITEMS],
        );
        $originalSalesOrderItemIdsToDelete = array_diff($originalSalesOrderItemIds, $updatedOrderItemIds);

        if (count($originalSalesOrderItemIdsToDelete) > 0) {
            foreach ($originalSalesOrderItemIdsToDelete as $orderItemId) {
                $log->deleteLog($orderItemId);
            }
        }

        return $returnData[static::RETURN_DATA_UPDATED_ORDER_ITEMS];
    }
}
