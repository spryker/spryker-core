<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

/**
 * Interface OrderStateMachineInterface
 * @package SprykerFeature\Zed\Oms\Business\Model
 */
interface OrderStateMachineInterface
{
    /**
     * @param string                                                     $eventId
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem[] $orderItems
     * @param array                                                      $data
     * @param array                                                      $logContext
     * @return array
     */
    public function triggerEvent($eventId, array $orderItems, $data, array $logContext = array());

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem[] $orderItems
     * @param array                                                      $data
     * @param array                                                      $logContext
     * @return array
     */
    public function triggerEventForNewItem(array $orderItems, array $data, array $logContext = array());

    /**
     * @param array $logContext
     * @return int
     */
    public function checkConditions(array $logContext = array());
}
