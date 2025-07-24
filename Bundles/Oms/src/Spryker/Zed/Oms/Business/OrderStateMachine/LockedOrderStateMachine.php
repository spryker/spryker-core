<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
use Spryker\Zed\Oms\Business\Lock\LockerInterface;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class LockedOrderStateMachine implements OrderStateMachineInterface
{
    use OrderStateOrderItemsFetchTrait;

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface $stateMachine
     * @param \Spryker\Zed\Oms\Business\Lock\LockerInterface $triggerLocker
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $activeProcesses
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\OmsConfig $omsConfig
     */
    public function __construct(
        protected OrderStateMachineInterface $stateMachine,
        protected LockerInterface $triggerLocker,
        protected BuilderInterface $builder,
        protected ReadOnlyArrayObject $activeProcesses,
        protected OmsQueryContainerInterface $queryContainer,
        protected OmsConfig $omsConfig
    ) {
    }

    /**
     * @param string $eventId
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEvent($eventId, array $orderItems, $data)
    {
        $triggerEventResult = null;
        $identifiers = $this->acquireTriggerLocker($orderItems);

        try {
            $triggerEventResult = $this->stateMachine->triggerEvent($eventId, $orderItems, $data);
        } finally {
            $this->triggerLocker->release($identifiers);
        }

        return $triggerEventResult;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForNewItem(array $orderItems, $data)
    {
        $triggerEventResult = null;
        $identifiers = $this->acquireTriggerLocker($orderItems);

        try {
            $triggerEventResult = $this->stateMachine->triggerEventForNewItem($orderItems, $data);
        } finally {
            $this->triggerLocker->release($identifiers);
        }

        return $triggerEventResult;
    }

    /**
     * @param array $logContext
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     *
     * @return int
     */
    public function checkConditions(array $logContext = [], ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer = null)
    {
        if (!($this->stateMachine instanceof CheckConditionForProcessAwareInterface)) {
            return $this->stateMachine->checkConditions($logContext, $omsCheckConditionsQueryCriteriaTransfer);
        }
        $affectedOrderItems = 0;
        foreach ($this->activeProcesses as $processName) {
            $process = $this->builder->createProcess($processName);
            $transitions = $process->getAllTransitionsWithoutEvent();
            $stateToTransitionsMap = $this->createStateToTransitionMap($transitions);
            $orderItems = $this->getOrderItemsByState(array_keys($stateToTransitionsMap), $process, $omsCheckConditionsQueryCriteriaTransfer);
            $orderItemIds = $this->collectIdentifiersForOrderItemsLock($orderItems);
            $this->acquireTriggerLockerByOrderItemIds($orderItemIds);

            try {
                $orderStateMachine = clone $this->stateMachine;
                $affectedOrderItems += $orderStateMachine
                    ->checkConditionsForProcess(
                        $process,
                        $omsCheckConditionsQueryCriteriaTransfer,
                        $stateToTransitionsMap,
                        $orderItems,
                    );
            } finally {
                $this->triggerLocker->release($orderItemIds);
            }
        }

        return $affectedOrderItems;
    }

    /**
     * @param array $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, $data)
    {
        $triggerEventResult = null;
        $identifiers = $this->acquireTriggerLockerByOrderItemIds($orderItemIds);

        try {
            $triggerEventResult = $this->stateMachine->triggerEventForNewOrderItems($orderItemIds, $data);
        } finally {
            $this->triggerLocker->release($identifiers);
        }

        return $triggerEventResult;
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
        $triggerEventResult = null;
        $identifiers = $this->acquireTriggerLockerByOrderItemIds([$orderItemId]);

        try {
            $triggerEventResult = $this->stateMachine->triggerEventForOneOrderItem($eventId, $orderItemId, $data);
        } finally {
            $this->triggerLocker->release($identifiers);
        }

        return $triggerEventResult;
    }

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, $data)
    {
        $triggerEventResult = null;
        $identifiers = $this->acquireTriggerLockerByOrderItemIds($orderItemIds);

        try {
            $triggerEventResult = $this->stateMachine->triggerEventForOrderItems($eventId, $orderItemIds, $data);
        } finally {
            $this->triggerLocker->release($identifiers);
        }

        return $triggerEventResult;
    }

    /**
     * @param array $orderItems
     *
     * @return array
     */
    protected function acquireTriggerLocker(array $orderItems)
    {
        $orderItemIds = $this->collectIdentifiersForOrderItemsLock($orderItems);

        return $this->acquireTriggerLockerByOrderItemIds($orderItemIds);
    }

    /**
     * @param array $orderItemIds
     *
     * @return array
     */
    protected function acquireTriggerLockerByOrderItemIds(array $orderItemIds)
    {
        $this->triggerLocker->acquire($orderItemIds);

        return $orderItemIds;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     *
     * @return array
     */
    protected function collectIdentifiersForOrderItemsLock(array $orderItems)
    {
        $orderItemIds = [];
        foreach ($orderItems as $orderItem) {
            $orderItemIds[] = $orderItem->getIdSalesOrderItem();
        }

        return $orderItemIds;
    }

    /**
     * @codeCoverageIgnore
     *
     * @deprecated Since we are creating one lock entry per item this method is not needed anymore.
     *
     * @param array $orderItemIds
     *
     * @return string
     */
    protected function buildIdentifierForOrderItemIdsLock(array $orderItemIds)
    {
        $orderItemIds = array_unique($orderItemIds);
        asort($orderItemIds);
        $identifier = implode('-', $orderItemIds);
        $identifier = hash('sha512', $identifier);

        return $identifier;
    }

    /**
     * @codeCoverageIgnore
     *
     * @deprecated Since we are creating one lock entry per item this method is not needed anymore.
     *
     * @param array $orderItemIds
     *
     * @return string|null
     */
    protected function buildDetails(array $orderItemIds)
    {
        if (!$orderItemIds) {
            return null;
        }

        /** @phpstan-var string */
        return json_encode([
            'id_sales_order_items' => $orderItemIds,
        ]);
    }
}
