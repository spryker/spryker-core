<?php

namespace SprykerFeature\Zed\Oms\Business\Model\Util;

use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use PropelObjectCollection;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\EventInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;

/**
 * Class TransitionLog
 * @package SprykerFeature\Zed\Oms\Business\Model\Util
 */
interface TransitionLogInterface
{
    /**
     * @param EventInterface $event
     */
    public function setEvent(EventInterface $event);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem[] $items
     */
    public function addItems(array $items);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @param CommandInterface                                         $command
     */
    public function addCommand(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item, CommandInterface $command);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @param ConditionInterface                                       $condition
     */
    public function addCondition(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item, ConditionInterface $condition);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @param StatusInterface                                          $status
     */
    public function addSourceStatus(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item, StatusInterface $status);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @param StatusInterface                                          $status
     */
    public function addTargetStatus(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item, StatusInterface $status);

    /**
     * @param TODO $error
     */
    public function setError($error);

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     */
    public function save($orderItem);

    /**
     * @return void
     */
    public function saveAll();

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return array|mixed|PropelObjectCollection
     */
    public function getLogForOrder(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order);
}
