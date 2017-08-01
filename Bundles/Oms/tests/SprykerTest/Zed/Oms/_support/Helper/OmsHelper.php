<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Helper;

use Codeception\Module;
use DateInterval;
use Orm\Zed\Oms\Persistence\SpyOmsEventTimeoutQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Oms\Business\OmsFacade;
use Symfony\Component\Process\Process;

class OmsHelper extends Module
{

    /**
     * @param array $idSalesOrderItems
     *
     * @return void
     */
    public function triggerEventForNewOrderItems(array $idSalesOrderItems)
    {
        $omsFacade = new OmsFacade();
        $omsFacade->triggerEventForNewOrderItems($idSalesOrderItems);
    }

    /**
     * @param int $idSalesOrderItem
     * @param \DateInterval $timeout
     *
     * @return void
     */
    public function moveItemAfterTimeOut($idSalesOrderItem, DateInterval $timeout)
    {
        $omsEventTimeoutQuery = new SpyOmsEventTimeoutQuery();
        $omsEventTimeout = $omsEventTimeoutQuery->findOneByFkSalesOrderItem($idSalesOrderItem);
        $dateTime = clone $omsEventTimeout->getTimeout();
        $dateTime->sub($timeout);
        $omsEventTimeout->setTimeout($dateTime);
        $omsEventTimeout->save();
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $stateName
     *
     * @return void
     */
    public function setItemState($idSalesOrderItem, $stateName)
    {
        $salesOrderItemQuery = new SpySalesOrderItemQuery();
        $salesOrderItemEntity = $salesOrderItemQuery->findOneByIdSalesOrderItem($idSalesOrderItem);

        $orderItemStateQuery = new SpyOmsOrderItemStateQuery();
        $orderItemStateEntity = $orderItemStateQuery->filterByName($stateName)->findOneOrCreate();
        $orderItemStateEntity->save();

        $salesOrderItemEntity->setState($orderItemStateEntity);
        $salesOrderItemEntity->save();
    }

    /**
     * @return void
     */
    public function checkCondition()
    {
        $this->runCommand('vendor/bin/console oms:check-condition -q');
    }

    /**
     * @return void
     */
    public function checkTimeout()
    {
        $this->runCommand('vendor/bin/console oms:check-timeout -q');
    }

    /**
     * @return void
     */
    public function clearLocks()
    {
        $this->runCommand('vendor/bin/console oms:check-locks -q');
    }

    /**
     * Used Symfony Process because console application uses call to exit()`
     *
     * @param string $command
     *
     * @return void
     */
    protected function runCommand($command)
    {
        $process = new Process($command);
        $process->run();
    }

}
