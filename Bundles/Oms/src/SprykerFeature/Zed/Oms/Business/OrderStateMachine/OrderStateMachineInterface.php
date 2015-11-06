<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface OrderStateMachineInterface
{

    /**
     * @param string $eventId
     * @param SpySalesOrderItem[] $orderItems
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, array $orderItems, $data);

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param array $data
     * @param array $logContext
     *
     * @return array
     */
    public function triggerEventForNewItem(array $orderItems, $data);

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
    public function triggerEventForNewOrderItems(array $orderItemIds, $data);

    /**
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, $data);

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, $data);

}
