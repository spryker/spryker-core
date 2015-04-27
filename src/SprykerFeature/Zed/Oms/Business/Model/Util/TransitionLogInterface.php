<?php

namespace SprykerFeature\Zed\Oms\Business\Model\Util;

use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\EventInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsTransitionLog;

interface TransitionLogInterface
{
    /**
     * @param EventInterface $event
     */
    public function setEvent(EventInterface $event);

    /**
     * @param SpySalesOrderItem[] $items
     */
    public function addItems(array $items);

    /**
     * @param SpySalesOrderItem $item
     * @param CommandInterface $command
     */
    public function addCommand(SpySalesOrderItem $item, CommandInterface $command);

    /**
     * @param SpySalesOrderItem $item
     * @param ConditionInterface $condition
     */
    public function addCondition(SpySalesOrderItem $item, ConditionInterface $condition);

    /**
     * @param SpySalesOrderItem $item
     * @param StatusInterface $status
     */
    public function addSourceStatus(SpySalesOrderItem $item, StatusInterface $status);

    /**
     * @param SpySalesOrderItem $item
     * @param StatusInterface $status
     */
    public function addTargetStatus(SpySalesOrderItem $item, StatusInterface $status);

    /**
     * @param $error
     */
    public function setError($error);

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage);

    /**
     * @param SpySalesOrderItem $orderItem
     */
    public function save($orderItem);

    /**
     * @return void
     */
    public function saveAll();

    /**
     * @param SpySalesOrder $order
     * @return SpyOmsTransitionLog[]
     */
    public function getLogForOrder(SpySalesOrder $order);
}
