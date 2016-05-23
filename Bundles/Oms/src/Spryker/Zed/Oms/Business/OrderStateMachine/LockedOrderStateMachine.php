<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Spryker\Zed\Oms\Business\Exception\LockException;
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
     * @throws \Spryker\Zed\Oms\Business\Exception\LockException
     *
     * @return array
     */
    public function triggerEvent($eventId, array $orderItems, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemsLock($orderItems);
        if ($this->triggerLocker->isLocked($identifier)) {
            throw new LockException('State machine trigger is locked.');
        }

        $this->triggerLocker->acquire($identifier);
        $triggerEventResult = $this->stateMachine->triggerEvent($eventId, $orderItems, $data);
        $this->triggerLocker->release($identifier);

        return $triggerEventResult;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param array $data
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\LockException
     *
     * @return array
     */
    public function triggerEventForNewItem(array $orderItems, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemsLock($orderItems);
        if ($this->triggerLocker->isLocked($identifier)) {
            throw new LockException('State machine trigger is locked.');
        }

        $this->triggerLocker->acquire($identifier);
        $triggerEventResult = $this->stateMachine->triggerEventForNewItem($orderItems, $data);
        $this->triggerLocker->release($identifier);

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
     * @throws \Spryker\Zed\Oms\Business\Exception\LockException
     *
     * @return array
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemIdsLock($orderItemIds);
        if ($this->triggerLocker->isLocked($identifier)) {
            throw new LockException('State machine trigger is locked.');
        }

        $this->triggerLocker->acquire($identifier);
        $triggerEventResult = $this->stateMachine->triggerEventForNewOrderItems($orderItemIds, $data);
        $this->triggerLocker->release($identifier);

        return $triggerEventResult;
    }

    /**
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\LockException
     *
     * @return array
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemIdsLock([$orderItemId]);
        if ($this->triggerLocker->isLocked($identifier)) {
            throw new LockException('State machine trigger is locked.');
        }

        $this->triggerLocker->acquire($identifier);
        $triggerEventResult = $this->stateMachine->triggerEventForOneOrderItem($eventId, $orderItemId, $data);
        $this->triggerLocker->release($identifier);

        return $triggerEventResult;
    }

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\LockException
     *
     * @return array
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, $data)
    {
        $identifier = $this->buildIdentifierForOrderItemIdsLock($orderItemIds);
        if ($this->triggerLocker->isLocked($identifier)) {
            throw new LockException('State machine trigger is locked.');
        }

        $this->triggerLocker->acquire($identifier);
        $triggerEventResult = $this->stateMachine->triggerEventForOrderItems($eventId, $orderItemIds, $data);
        $this->triggerLocker->release($identifier);

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
        $identifier = implode('-', $orderItemIds);
        return $identifier;
    }

}
