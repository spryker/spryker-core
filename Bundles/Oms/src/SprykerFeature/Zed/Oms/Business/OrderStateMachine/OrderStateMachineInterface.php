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
    public function triggerEvent($eventId, array $orderItems, $data, array $logContext = array());

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param array $data
     * @param array $logContext
     * @return array
     */
    public function triggerEventForNewItem(array $orderItems, array $data, array $logContext = array());

    /**
     * @param array $logContext
     * @return int
     */
    public function checkConditions(array $logContext = array());
}
