<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

interface OrderStateMachineInterface
{

    /**
     * @param string $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param array $data
     * @param array $logContext
     *
     * @return array
     */
    public function triggerEvent($eventId, array $orderItems, $data, array $logContext = []);

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param array $data
     * @param array $logContext
     *
     * @return array
     */
    public function triggerEventForNewItem(array $orderItems, array $data, array $logContext = []);

    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkConditions(array $logContext = []);

    /**
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, array $data);

    /**
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, array $data);

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, array $data);

}
