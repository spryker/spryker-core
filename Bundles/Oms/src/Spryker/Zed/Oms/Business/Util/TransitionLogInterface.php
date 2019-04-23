<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Process\EventInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

interface TransitionLogInterface
{
    /**
     * @param \Spryker\Zed\Oms\Business\Process\EventInterface $event
     *
     * @return void
     */
    public function setEvent(EventInterface $event);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return void
     */
    public function init(array $salesOrderItems);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $item
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface $command
     *
     * @return void
     */
    public function addCommand(SpySalesOrderItem $item, CommandInterface $command);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $item
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface $condition
     *
     * @return void
     */
    public function addCondition(SpySalesOrderItem $item, ConditionInterface $condition);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $item
     * @param string $stateName
     *
     * @return void
     */
    public function addSourceState(SpySalesOrderItem $item, $stateName);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $item
     * @param string $stateName
     *
     * @return void
     */
    public function addTargetState(SpySalesOrderItem $item, $stateName);

    /**
     * @param bool $error
     *
     * @return void
     */
    public function setIsError($error);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return void
     */
    public function save(SpySalesOrderItem $salesOrderItem);

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    public function setErrorMessage($errorMessage);

    /**
     * @return void
     */
    public function saveAll();

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLog[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getLogForOrder(SpySalesOrder $order);
}
