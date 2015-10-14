<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Util;

use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Oms\Business\Process\StateInterface;
use SprykerFeature\Zed\Oms\Business\Process\EventInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLog;

interface TransitionLogInterface
{

    /**
     * @param EventInterface $event
     */
    public function setEvent(EventInterface $event);

    /**
     * @param SpySalesOrderItem[] $salesOrderItems
     */
    public function init(array $salesOrderItems);

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
     * @param string $stateName
     */
    public function addSourceState(SpySalesOrderItem $item, $stateName);

    /**
     * @param SpySalesOrderItem $item
     * @param string $stateName
     */
    public function addTargetState(SpySalesOrderItem $item, $stateName);

    /**
     * @param bool $error
     */
    public function setError($error);

    /**
     * @param SpySalesOrderItem $salesOrderItem
     * @return void
     */
    public function save(SpySalesOrderItem $salesOrderItem);

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage);

    /**
     */
    public function saveAll();

    /**
     * @param SpySalesOrder $order
     *
     * @return SpyOmsTransitionLog[]
     */
    public function getLogForOrder(SpySalesOrder $order);

}
