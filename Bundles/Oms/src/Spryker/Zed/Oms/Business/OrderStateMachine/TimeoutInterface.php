<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use DateTime;
use Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;

interface TimeoutInterface
{
    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface $orderStateMachine
     * @param \Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer|null $omsCheckTimeoutsQueryCriteriaTransfer
     *
     * @return int
     */
    public function checkTimeouts(
        OrderStateMachineInterface $orderStateMachine,
        ?OmsCheckTimeoutsQueryCriteriaTransfer $omsCheckTimeoutsQueryCriteriaTransfer = null
    );

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
