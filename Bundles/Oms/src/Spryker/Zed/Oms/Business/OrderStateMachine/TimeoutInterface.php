<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use DateTime;

interface TimeoutInterface
{

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface $orderStateMachine
     *
     * @return int
     */
    public function checkTimeouts(OrderStateMachineInterface $orderStateMachine);

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \DateTime $currentTime
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function setNewTimeout(ProcessInterface $process, SpySalesOrderItem $orderItem, DateTime $currentTime);

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string $stateId
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function dropOldTimeout(ProcessInterface $process, $stateId, SpySalesOrderItem $orderItem);

}
