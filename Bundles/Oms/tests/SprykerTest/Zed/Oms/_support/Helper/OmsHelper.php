<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Helper;

use Codeception\Module;
use DateInterval;
use Generated\Shared\DataBuilder\OmsProductReservationBuilder;
use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsEventTimeout;
use Orm\Zed\Oms\Persistence\SpyOmsEventTimeoutQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Zed\Oms\Business\OmsFacade;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use Symfony\Component\Process\Process;

class OmsHelper extends Module
{
    use DataCleanupHelperTrait;
    use ConfigHelperTrait;

    /**
     * @param array $idSalesOrderItems
     *
     * @return void
     */
    public function triggerEventForNewOrderItems(array $idSalesOrderItems): void
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
    public function moveItemAfterTimeOut(int $idSalesOrderItem, DateInterval $timeout): void
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
    public function setItemState(int $idSalesOrderItem, string $stateName): void
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
    public function checkCondition(): void
    {
        $this->runCommand('vendor/bin/console oms:check-condition -q');
    }

    /**
     * @return void
     */
    public function checkTimeout(): void
    {
        $this->runCommand('vendor/bin/console oms:check-timeout -q');
    }

    /**
     * @return void
     */
    public function clearLocks(): void
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
    protected function runCommand(string $command): void
    {
        $process = new Process(explode(' ', $command));
        $process->run();
    }

    /**
     * @param array $activeProcesses
     * @param string|null $xmlFolder
     *
     * @return void
     */
    public function configureTestStateMachine(array $activeProcesses, ?string $xmlFolder = null): void
    {
        if (!$xmlFolder) {
            $xmlFolder = realpath(__DIR__ . '/../../../../../_data/state-machine/');
        }

        $this->setConfig(OmsConstants::PROCESS_LOCATION, $xmlFolder);
        $this->setConfig(OmsConstants::ACTIVE_PROCESSES, $activeProcesses);
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\OmsProductReservationTransfer
     */
    public function haveOmsProductReservation(array $seed): OmsProductReservationTransfer
    {
        $omsProductReservationTransfer = new OmsProductReservationBuilder($seed);
        $omsProductReservationTransfer = $omsProductReservationTransfer->build();

        $omsProductReservationEntity = (new SpyOmsProductReservation());
        $omsProductReservationEntity->fromArray($omsProductReservationTransfer->toArray());
        $omsProductReservationEntity->save();

        $omsProductReservationTransfer->fromArray($omsProductReservationEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($omsProductReservationEntity): void {
            $omsProductReservationEntity->delete();
        });

        return $omsProductReservationTransfer;
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function haveOmsOrderItemStateEntity(string $name): SpyOmsOrderItemState
    {
        $omsOrderItemState = SpyOmsOrderItemStateQuery::create()->filterByName($name)->findOneOrCreate();
        $omsOrderItemState->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($omsOrderItemState): void {
            $omsOrderItemState->delete();
        });

        return $omsOrderItemState;
    }

    /**
     * @param array $seed
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsEventTimeout
     */
    public function haveOmsEventTimeoutEntity(array $seed): SpyOmsEventTimeout
    {
        $omsEventTimeoutQuery = SpyOmsEventTimeoutQuery::create();
        if (isset($seed['fk_oms_order_item_state'])) {
            $omsEventTimeoutQuery->filterByFkOmsOrderItemState($seed['fk_oms_order_item_state']);
        }
        if (isset($seed['fk_sales_order_item'])) {
            $omsEventTimeoutQuery->filterByFkSalesOrderItem($seed['fk_sales_order_item']);
        }
        if (isset($seed['event'])) {
            $omsEventTimeoutQuery->filterByEvent($seed['event']);
        }

        $omsEventTimeout = $omsEventTimeoutQuery->findOneOrCreate();

        if (isset($seed['timeout'])) {
            $omsEventTimeout->setTimeout($seed['timeout']);
        }

        $omsEventTimeout->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($omsEventTimeout): void {
            $omsEventTimeout->delete();
        });

        return $omsEventTimeout;
    }
}
