<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Spryker\Zed\Oms\Business\Lock\LockerInterface;

class LockedOrderStateMachine implements OrderStateMachineInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface
     */
    protected $stateMachine;

    /**
     * @var \Spryker\Zed\Oms\Business\Lock\LockerInterface
     */
    protected $triggerLocker;

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface $stateMachine
     * @param \Spryker\Zed\Oms\Business\Lock\LockerInterface $triggerLocker
     */
    public function __construct(OrderStateMachineInterface $stateMachine, LockerInterface $triggerLocker)
    {
        $this->stateMachine = $stateMachine;
        $this->triggerLocker = $triggerLocker;
    }

    /**
     * @param string $eventId
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEvent($eventId, array $orderItems, $data)
    {
        $triggerEventResult = null;
        $identifier = $this->acquireTriggerLocker($orderItems);

        try {
            $triggerEventResult = $this->stateMachine->triggerEvent($eventId, $orderItems, $data);
        } finally {
            $this->triggerLocker->release($identifier);
        }

        return $triggerEventResult;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForNewItem(array $orderItems, $data)
    {
        $triggerEventResult = null;
        $identifier = $this->acquireTriggerLocker($orderItems);

        try {
            $triggerEventResult = $this->stateMachine->triggerEventForNewItem($orderItems, $data);
        } finally {
            $this->triggerLocker->release($identifier);
        }

        return $triggerEventResult;
    }

    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkConditions(array $logContext = [])
    {
        return $this->stateMachine->checkConditions($logContext);
    }

    /**
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, $data)
    {
        $triggerEventResult = null;
        $identifier = $this->acquireTriggerLockerByOrderItemIds($orderItemIds);

        try {
            $triggerEventResult = $this->stateMachine->triggerEventForNewOrderItems($orderItemIds, $data);
        } finally {
            $this->triggerLocker->release($identifier);
        }

        return $triggerEventResult;
    }

    /**
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, $data)
    {
        $triggerEventResult = null;
        $identifier = $this->acquireTriggerLockerByOrderItemIds([$orderItemId]);

        try {
            $triggerEventResult = $this->stateMachine->triggerEventForOneOrderItem($eventId, $orderItemId, $data);
        } finally {
            $this->triggerLocker->release($identifier);
        }

        return $triggerEventResult;
    }

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, $data)
    {
        $triggerEventResult = null;
        $identifier = $this->acquireTriggerLockerByOrderItemIds($orderItemIds);

        try {
            $triggerEventResult = $this->stateMachine->triggerEventForOrderItems($eventId, $orderItemIds, $data);
        } finally {
            $this->triggerLocker->release($identifier);
        }

        return $triggerEventResult;
    }

    /**
     * @param array $orderItems
     *
     * @return string
     */
    protected function acquireTriggerLocker(array $orderItems)
    {
        $orderItemIds = $this->collectIdentifiersForOrderItemsLock($orderItems);

        return $this->acquireTriggerLockerByOrderItemIds($orderItemIds);
    }

    /**
     * @param array $orderItemIds
     *
     * @return string
     */
    protected function acquireTriggerLockerByOrderItemIds(array $orderItemIds)
    {
        $identifier = $this->buildIdentifierForOrderItemIdsLock($orderItemIds);
        $details = $this->buildDetails($orderItemIds);

        $this->triggerLocker->acquire($identifier, $details);

        return $identifier;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
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
     * @param array $orderItemIds
     *
     * @return string|null
     */
    protected function buildDetails(array $orderItemIds)
    {
        if (empty($orderItemIds)) {
            return null;
        }

        return json_encode([
            'id_sales_order_items' => $orderItemIds,
        ]);
    }
}
