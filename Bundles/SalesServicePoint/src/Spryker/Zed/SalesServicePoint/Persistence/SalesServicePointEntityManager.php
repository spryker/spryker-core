<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;
use Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

/**
 * @method \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointPersistenceFactory getFactory()
 */
class SalesServicePointEntityManager extends AbstractEntityManager implements SalesServicePointEntityManagerInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer
     */
    public function createSalesOrderItemServicePoint(
        SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
    ): SalesOrderItemServicePointTransfer {
        $salesServicePointMapper = $this->getFactory()->createSalesServicePointMapper();
        $salesOrderItemServicePointEntity = $salesServicePointMapper->mapSalesOrderItemServicePointTransferToSalesOrderItemServicePointEntity(
            $salesOrderItemServicePointTransfer,
            new SpySalesOrderItemServicePoint(),
        );

        $salesOrderItemServicePointEntity->save();

        return $salesServicePointMapper->mapSalesOrderItemServicePointEntityToSalesOrderItemServicePointTransfer(
            $salesOrderItemServicePointEntity,
            $salesOrderItemServicePointTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer
     */
    public function saveSalesOrderItemServicePointByFkSalesOrderItem(
        SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
    ): SalesOrderItemServicePointTransfer {
        $salesOrderItemServicePointEntity = $this->getFactory()
            ->getSalesOrderItemServicePointQuery()
            ->filterByFkSalesOrderItem($salesOrderItemServicePointTransfer->getIdSalesOrderItemOrFail())
            ->findOneOrCreate();

        $salesServicePointMapper = $this->getFactory()->createSalesServicePointMapper();
        $salesOrderItemServicePointEntity = $salesServicePointMapper
            ->mapSalesOrderItemServicePointTransferToSalesOrderItemServicePointEntity(
                $salesOrderItemServicePointTransfer,
                $salesOrderItemServicePointEntity,
            );

        $salesOrderItemServicePointEntity->save();

        return $salesServicePointMapper->mapSalesOrderItemServicePointEntityToSalesOrderItemServicePointTransfer(
            $salesOrderItemServicePointEntity,
            $salesOrderItemServicePointTransfer,
        );
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemServicePointsBySalesOrderItemIds(array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->getSalesOrderItemServicePointQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer
     */
    protected function createSalesOrderItemServicePointTransfer(ItemTransfer $itemTransfer): SalesOrderItemServicePointTransfer
    {
        $servicePointTransfer = $itemTransfer->getServicePointOrFail();

        return (new SalesOrderItemServicePointTransfer())
            ->setName($servicePointTransfer->getNameOrFail())
            ->setKey($servicePointTransfer->getKeyOrFail())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail());
    }
}
