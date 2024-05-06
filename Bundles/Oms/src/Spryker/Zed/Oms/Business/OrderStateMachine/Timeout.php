<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use DateInterval;
use DateTime;
use ErrorException;
use Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer;
use Generated\Shared\Transfer\OmsEventTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Generated\Shared\Transfer\TimeoutProcessorTimeoutRequestTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsEventTimeout;
use Orm\Zed\Oms\Persistence\SpyOmsEventTimeoutQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\Collection;
use RuntimeException;
use Spryker\Zed\Oms\Business\Process\EventInterface;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollectionInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

class Timeout implements TimeoutInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Oms\OmsConfig
     */
    protected $omsConfig;

    /**
     * @var array<\DateTime>
     */
    protected $eventToTimeoutBuffer = [];

    /**
     * @var array<\Spryker\Zed\Oms\Business\Process\StateInterface>
     */
    protected $stateIdToModelBuffer = [];

    /**
     * @var \Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollectionInterface
     */
    protected $timeoutProcessorCollection;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollectionInterface $timeoutProcessorCollection
     * @param \Spryker\Zed\Oms\OmsConfig $omsConfig
     */
    public function __construct(
        OmsQueryContainerInterface $queryContainer,
        TimeoutProcessorCollectionInterface $timeoutProcessorCollection,
        OmsConfig $omsConfig
    ) {
        $this->queryContainer = $queryContainer;
        $this->timeoutProcessorCollection = $timeoutProcessorCollection;
        $this->omsConfig = $omsConfig;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface $orderStateMachine
     * @param \Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer|null $omsCheckTimeoutsQueryCriteriaTransfer
     *
     * @return int
     */
    public function checkTimeouts(
        OrderStateMachineInterface $orderStateMachine,
        ?OmsCheckTimeoutsQueryCriteriaTransfer $omsCheckTimeoutsQueryCriteriaTransfer = null
    ) {
        $orderItems = $this->findItemsWithExpiredTimeouts($omsCheckTimeoutsQueryCriteriaTransfer);

        $countAffectedItems = $orderItems->count();

        $groupedOrderItems = $this->groupItemsByEvent($orderItems);

        foreach ($groupedOrderItems as $orderData) {
            foreach ($orderData as $event => $orderItems) {
                $orderStateMachine->triggerEvent($event, $orderItems, []);
            }
        }

        return $countAffectedItems;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \DateTime $currentTime
     *
     * @return void
     */
    public function setNewTimeout(ProcessInterface $process, SpySalesOrderItem $orderItem, DateTime $currentTime)
    {
        $newOmsEventTimeoutEntities = $this->getNewOmsEventTimeoutEntities($orderItem, $process, $currentTime);

        foreach ($newOmsEventTimeoutEntities as $newOmsEventTimeoutEntity) {
            $newOmsEventTimeoutEntity->save();
        }
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string $stateId
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return void
     */
    public function dropOldTimeout(ProcessInterface $process, $stateId, SpySalesOrderItem $orderItem)
    {
        $sourceState = $this->getStateFromProcess($stateId, $process);

        if ($sourceState->hasTimeoutEvent()) {
            SpyOmsEventTimeoutQuery::create()
                ->filterByOrderItem($orderItem)
                ->delete();
        }
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \DateTime $currentTime
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
     *
     * @return void
     */
    public function setNewTimeouts(array $orderItems, DateTime $currentTime, array $processes): void
    {
        $newOmsEventTimeoutEntities = [];

        foreach ($orderItems as $orderItem) {
            $process = $processes[$orderItem->getProcess()->getName()];

            $newOmsEventTimeoutEntities = array_merge(
                $newOmsEventTimeoutEntities,
                $this->getNewOmsEventTimeoutEntities($orderItem, $process, $currentTime),
            );
        }

        if ($newOmsEventTimeoutEntities !== []) {
            foreach ($newOmsEventTimeoutEntities as $newOmsEventTimeoutEntity) {
                $this->persist($newOmsEventTimeoutEntity);
            }

            $this->commitIdentical();
        }
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<\Spryker\Zed\Oms\Business\Process\ProcessInterface> $processes
     * @param array $sourceStateBuffer
     *
     * @return void
     */
    public function dropOldTimeouts(array $orderItems, array $processes, array $sourceStateBuffer): void
    {
        $orderItemIdsForRemoving = [];

        foreach ($orderItems as $orderItem) {
            $process = $processes[$orderItem->getProcess()->getName()];

            $sourceStateId = $sourceStateBuffer[$orderItem->getIdSalesOrderItem()];
            $targetStateId = $orderItem->getState()->getName();
            $targetState = $this->getStateFromProcess($targetStateId, $process);
            $sourceState = $this->getStateFromProcess($sourceStateId, $process);

            if ($targetState->hasTimeoutEvent() && $sourceState->hasTimeoutEvent()) {
                $orderItemIdsForRemoving[] = $orderItem->getIdSalesOrderItem();
            }
        }

        SpyOmsEventTimeoutQuery::create()
            ->filterByFkSalesOrderItem_In($orderItemIdsForRemoving)
            ->delete();
    }

    /**
     * @param \DateTime $currentTime
     * @param \Spryker\Zed\Oms\Business\Process\EventInterface $event
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem|null $spySalesOrderItem
     *
     * @throws \RuntimeException
     *
     * @return \DateTime
     */
    protected function calculateTimeoutDateFromEvent(DateTime $currentTime, EventInterface $event, ?SpySalesOrderItem $spySalesOrderItem = null)
    {
        if (isset($this->eventToTimeoutBuffer[$event->getName()])) {
            return $this->eventToTimeoutBuffer[$event->getName()];
        }

        $currentTime = clone $currentTime;

        if ($spySalesOrderItem && $event->hasTimeoutProcessor()) {
            $this->eventToTimeoutBuffer[$event->getName()] = $this->calculateTimeoutFromTimeoutProcessor($currentTime, $event, $spySalesOrderItem);

            return $this->eventToTimeoutBuffer[$event->getName()];
        }

        $timeout = $event->getTimeout();
        $interval = DateInterval::createFromDateString($timeout);
        if ($interval === false) {
            throw new RuntimeException('Cannot create a DateInterval from `$event->getTimeout()`');
        }

        $this->validateTimeout($interval, $timeout);

        $this->eventToTimeoutBuffer[$event->getName()] = $currentTime->add($interval);

        return $this->eventToTimeoutBuffer[$event->getName()];
    }

    /**
     * @param string $stateId
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     *
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    protected function getStateFromProcess($stateId, ProcessInterface $process)
    {
        if (!isset($this->stateIdToModelBuffer[$stateId])) {
            $this->stateIdToModelBuffer[$stateId] = $process->getStateFromAllProcesses($stateId);
        }

        return $this->stateIdToModelBuffer[$stateId];
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $orderItems
     *
     * @return array
     */
    protected function groupItemsByEvent(Collection $orderItems)
    {
        $groupedOrderItems = [];
        foreach ($orderItems as $orderItem) {
            $eventName = $orderItem->getEvent();
            $idSalesOrder = $orderItem->getFkSalesOrder();

            if (!isset($groupedOrderItems[$idSalesOrder][$eventName])) {
                $groupedOrderItems[$idSalesOrder][$eventName] = [];
            }

            $groupedOrderItems[$idSalesOrder][$eventName][] = $orderItem;
        }

        return $groupedOrderItems;
    }

    /**
     * @param \Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer|null $omsCheckTimeoutsQueryCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    protected function findItemsWithExpiredTimeouts(?OmsCheckTimeoutsQueryCriteriaTransfer $omsCheckTimeoutsQueryCriteriaTransfer = null)
    {
        $now = new DateTime('now');

        $omsCheckTimeoutsQueryCriteriaTransfer = $this->prepareOmsCheckTimeoutsQueryCriteriaTransfer($omsCheckTimeoutsQueryCriteriaTransfer);

        return $this->queryContainer
            ->querySalesOrderItemsWithExpiredTimeouts($now, $omsCheckTimeoutsQueryCriteriaTransfer)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer|null $omsCheckTimeoutsQueryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer
     */
    protected function prepareOmsCheckTimeoutsQueryCriteriaTransfer(
        ?OmsCheckTimeoutsQueryCriteriaTransfer $omsCheckTimeoutsQueryCriteriaTransfer = null
    ): OmsCheckTimeoutsQueryCriteriaTransfer {
        if ($omsCheckTimeoutsQueryCriteriaTransfer === null) {
            $omsCheckTimeoutsQueryCriteriaTransfer = new OmsCheckTimeoutsQueryCriteriaTransfer();
        }

        if ($omsCheckTimeoutsQueryCriteriaTransfer->getLimit() === null) {
            $omsCheckTimeoutsQueryCriteriaTransfer->setLimit($this->omsConfig->getCheckTimeoutsQueryLimit());
        }

        return $omsCheckTimeoutsQueryCriteriaTransfer;
    }

    /**
     * @param \DateInterval $interval
     * @param mixed $timeout
     *
     * @throws \ErrorException
     *
     * @return int
     */
    protected function validateTimeout($interval, $timeout)
    {
        $vars = get_object_vars($interval);
        $vSum = 0;
        foreach ($vars as $v) {
            $vSum += (int)$v;
        }
        if ($vSum === 0) {
            throw new ErrorException('Invalid format for timeout "' . $timeout . '"');
        }

        return $vSum;
    }

    /**
     * @param \DateTime $currentTime
     * @param \Spryker\Zed\Oms\Business\Process\EventInterface $event
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $spySalesOrderItem
     *
     * @return \DateTime
     */
    protected function calculateTimeoutFromTimeoutProcessor(
        DateTime $currentTime,
        EventInterface $event,
        SpySalesOrderItem $spySalesOrderItem
    ): DateTime {
        $spySalesOrderItemEntityTransfer = (new SpySalesOrderItemEntityTransfer())
            ->fromArray($spySalesOrderItem->toArray(), true);
        $omsEventTransfer = (new OmsEventTransfer())->setTimeout($event->getTimeout());
        $timeoutProcessorTimeoutRequestTransfer = (new TimeoutProcessorTimeoutRequestTransfer())
            ->setSalesOrderItemEntity($spySalesOrderItemEntityTransfer)
            ->setOmsEvent($omsEventTransfer)
            ->setTimestamp($currentTime->getTimestamp());

        $timeoutProcessor = $this->timeoutProcessorCollection->get((string)$event->getTimeoutProcessor());
        $timeoutProcessorTimeoutResponseTransfer = $timeoutProcessor->calculateTimeout($timeoutProcessorTimeoutRequestTransfer);

        return (new DateTime())->setTimestamp($timeoutProcessorTimeoutResponseTransfer->getTimeoutTimestamp());
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param \DateTime $currentTime
     *
     * @return array<\Orm\Zed\Oms\Persistence\SpyOmsEventTimeout>
     */
    protected function getNewOmsEventTimeoutEntities(
        SpySalesOrderItem $orderItem,
        ProcessInterface $process,
        DateTime $currentTime
    ): array {
        $targetStateEntity = $orderItem->getState();

        $targetState = $this->getStateFromProcess($targetStateEntity->getName(), $process);

        $omsEventTimeoutEntities = [];

        if ($targetState->hasTimeoutEvent()) {
            $events = $targetState->getTimeoutEvents();

            $handledEvents = [];
            foreach ($events as $event) {
                if (in_array($event->getName(), $handledEvents)) {
                    continue;
                }

                $handledEvents[] = $event->getName();
                $timeoutDate = $this->calculateTimeoutDateFromEvent($currentTime, $event, $orderItem);

                $omsEventTimeoutEntities[] = (new SpyOmsEventTimeout())
                    ->setTimeout($timeoutDate)
                    ->setOrderItem($orderItem)
                    ->setState($targetStateEntity)
                    ->setEvent($event->getName());
            }
        }

        return $omsEventTimeoutEntities;
    }
}
