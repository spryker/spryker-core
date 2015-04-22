<?php

namespace SprykerFeature\Zed\Oms\Business\Model\OrderStateMachine;
use SprykerFeature\Zed\Oms\Business\Model\OrderStateMachineInterface;
use SprykerFeature\Zed\Oms\Business\Model\ProcessInterface;

/**
 * Interface TimeoutInterface
 * @package SprykerFeature\Zed\Oms\Business\Model\OrderStateMachine
 */
interface TimeoutInterface
{
    /**
     * @param OrderStateMachineInterface $orderStateMachine
     * @return int
     */
    public function checkTimeouts(OrderStateMachineInterface $orderStateMachine);

    /**
     * @param ProcessInterface                                         $process
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @param \DateTime                                                $currentTime
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setNewTimeout(ProcessInterface $process, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem, \DateTime $currentTime);

    /**
     * @param ProcessInterface                                         $process
     * @param string                                                   $statusId
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function dropOldTimeout(ProcessInterface $process, $statusId, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem);
}
