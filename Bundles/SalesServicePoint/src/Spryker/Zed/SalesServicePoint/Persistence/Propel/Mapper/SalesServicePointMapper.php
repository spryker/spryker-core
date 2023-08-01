<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;
use Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint;
use Propel\Runtime\Collection\ObjectCollection;

class SalesServicePointMapper
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
     * @param \Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint $salesOrderItemServicePointEntity
     *
     * @return \Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint
     */
    public function mapSalesOrderItemServicePointTransferToSalesOrderItemServicePointEntity(
        SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer,
        SpySalesOrderItemServicePoint $salesOrderItemServicePointEntity
    ): SpySalesOrderItemServicePoint {
        $salesOrderItemServicePointEntity->fromArray($salesOrderItemServicePointTransfer->modifiedToArray());

        return $salesOrderItemServicePointEntity->setFkSalesOrderItem($salesOrderItemServicePointTransfer->getIdSalesOrderItemOrFail());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint> $salesOrderItemServicePointEntities
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer $salesOrderItemServicePointCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer
     */
    public function mapSalesOrderItemServicePointEntityCollectionToSalesOrderItemServicePointCollectionTransfer(
        ObjectCollection $salesOrderItemServicePointEntities,
        SalesOrderItemServicePointCollectionTransfer $salesOrderItemServicePointCollectionTransfer
    ): SalesOrderItemServicePointCollectionTransfer {
        foreach ($salesOrderItemServicePointEntities as $salesOrderItemServicePointEntity) {
            $salesOrderItemServicePointTransfer = $this->mapSalesOrderItemServicePointEntityToSalesOrderItemServicePointTransfer(
                $salesOrderItemServicePointEntity,
                new SalesOrderItemServicePointTransfer(),
            );

            $salesOrderItemServicePointCollectionTransfer->addSalesOrderItemServicePoint($salesOrderItemServicePointTransfer);
        }

        return $salesOrderItemServicePointCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\SalesServicePoint\Persistence\SpySalesOrderItemServicePoint $salesOrderItemServicePointEntity
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer
     */
    public function mapSalesOrderItemServicePointEntityToSalesOrderItemServicePointTransfer(
        SpySalesOrderItemServicePoint $salesOrderItemServicePointEntity,
        SalesOrderItemServicePointTransfer $salesOrderItemServicePointTransfer
    ): SalesOrderItemServicePointTransfer {
        $idSalesOrderItem = $salesOrderItemServicePointEntity->getFkSalesOrderItem();

        return $salesOrderItemServicePointTransfer
            ->fromArray($salesOrderItemServicePointEntity->toArray(), true)
            ->setIdSalesOrderItem($idSalesOrderItem);
    }
}
