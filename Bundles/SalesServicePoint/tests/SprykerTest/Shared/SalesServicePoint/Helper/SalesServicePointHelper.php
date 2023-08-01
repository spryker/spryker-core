<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SalesServicePoint\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\SalesOrderItemServicePointBuilder;
use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;
use Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint;
use Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePointQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class SalesServicePointHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer
     */
    public function haveSalesOrderItemServicePoint(array $seed = []): SalesOrderItemServicePointTransfer
    {
        $salesOrderItemServicePointTransfer = (new SalesOrderItemServicePointBuilder($seed))->build();
        $salesOrderItemServicePointEntity = (new SpySalesOrderItemServicePoint())
            ->fromArray($salesOrderItemServicePointTransfer->toArray())
            ->setFkSalesOrderItem($salesOrderItemServicePointTransfer->getIdSalesOrderItemOrFail());

        $salesOrderItemServicePointEntity->save();

        $salesOrderItemServicePointTransfer->fromArray($salesOrderItemServicePointEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($salesOrderItemServicePointEntity): void {
            $this->deleteSalesOrderItemServicePoint($salesOrderItemServicePointEntity->getIdSalesOrderItemServicePoint());
        });

        return $salesOrderItemServicePointTransfer;
    }

    /**
     * @param int $idSalesOrderItemServicePoint
     *
     * @return void
     */
    protected function deleteSalesOrderItemServicePoint(int $idSalesOrderItemServicePoint): void
    {
        $salesOrderItemServicePointEntity = $this->getSalesOrderItemServicePointQuery()
            ->findOneByIdSalesOrderItemServicePoint($idSalesOrderItemServicePoint);

        if ($salesOrderItemServicePointEntity) {
            $salesOrderItemServicePointEntity->delete();
        }
    }

    /**
     * @return \Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePointQuery
     */
    protected function getSalesOrderItemServicePointQuery(): SpySalesOrderItemServicePointQuery
    {
        return SpySalesOrderItemServicePointQuery::create();
    }
}
