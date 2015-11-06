<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use SprykerFeature\Zed\Oms\Business\Process\ProcessInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use DateTime;
use Exception;
use Propel\Runtime\Exception\PropelException;

interface TimeoutInterface
{

    /**
     * @param OrderStateMachineInterface $orderStateMachine
     *
     * @return int
     */
    public function checkTimeouts(OrderStateMachineInterface $orderStateMachine);

    /**
     * @param ProcessInterface $process
     * @param SpySalesOrderItem $orderItem
     * @param DateTime $currentTime
     *
     * @throws Exception
     * @throws PropelException
     *
     * @return void
     */
    public function setNewTimeout(ProcessInterface $process, SpySalesOrderItem $orderItem, DateTime $currentTime);

    /**
     * @param ProcessInterface $process
     * @param string $stateId
     * @param SpySalesOrderItem $orderItem
     *
     * @throws Exception
     * @throws PropelException
     *
     * @return void
     */
    public function dropOldTimeout(ProcessInterface $process, $stateId, SpySalesOrderItem $orderItem);

}
