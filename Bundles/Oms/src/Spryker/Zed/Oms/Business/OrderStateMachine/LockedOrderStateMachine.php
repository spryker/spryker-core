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
     * @return array
     */
    public function triggerEvent($eventId, array $orderItems, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemsLock($orderItems);
        $this->triggerLocker->acquire($identifier);
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
     * @return array
     */
    public function triggerEventForNewItem(array $orderItems, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemsLock($orderItems);
        $this->triggerLocker->acquire($identifier);
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
     * @return array
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemIdsLock($orderItemIds);
        $this->triggerLocker->acquire($identifier);
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
     * @return array
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemIdsLock([$orderItemId]);
        $this->triggerLocker->acquire($identifier);
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
     * @return array
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemIdsLock($orderItemIds);
        $this->triggerLocker->acquire($identifier);
        try {
            $triggerEventResult = $this->stateMachine->triggerEventForOrderItems($eventId, $orderItemIds, $data);
        } finally {
            $this->triggerLocker->release($identifier);
        }

        return $triggerEventResult;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return string
     */
    protected function buildIdentifierForOrderItemsLock(array $orderItems)
    {
        $orderItemIds = [];
        foreach ($orderItems as $orderItem) {
            $orderItemIds[] = $orderItem->getIdSalesOrderItem();
        }

        return $this->buildIdentifierForOrderItemIdsLock($orderItemIds);
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
}
