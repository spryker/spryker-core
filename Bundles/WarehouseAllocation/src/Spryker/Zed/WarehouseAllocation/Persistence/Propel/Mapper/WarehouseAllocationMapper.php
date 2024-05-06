<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer;
use Generated\Shared\Transfer\WarehouseAllocationTransfer;
use Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation;
use Propel\Runtime\Collection\Collection;

class WarehouseAllocationMapper
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseAllocationTransfer $warehouseAllocationTransfer
     * @param \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation $warehouseAllocationEntity
     *
     * @return \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation
     */
    public function mapWarehouseAllocationTransferToWarehouseAllocationEntity(
        WarehouseAllocationTransfer $warehouseAllocationTransfer,
        SpyWarehouseAllocation $warehouseAllocationEntity
    ): SpyWarehouseAllocation {
        return $warehouseAllocationEntity
            ->fromArray($warehouseAllocationTransfer->modifiedToArray())
            ->setFkWarehouse($warehouseAllocationTransfer->getWarehouseOrFail()->getIdStockOrFail());
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation> $warehouseAllocationEntities
     * @param \Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer $warehouseAllocationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer
     */
    public function mapWarehouseAllocationEntitiesToWarehouseAllocationCollectionTransfer(
        Collection $warehouseAllocationEntities,
        WarehouseAllocationCollectionTransfer $warehouseAllocationCollectionTransfer
    ): WarehouseAllocationCollectionTransfer {
        foreach ($warehouseAllocationEntities as $warehouseAllocationEntity) {
            $warehouseAllocationTransfer = $this->mapWarehouseAllocationEntityToWarehouseAllocationTransfer(
                $warehouseAllocationEntity,
                new WarehouseAllocationTransfer(),
            );

            $warehouseAllocationCollectionTransfer->addWarehouseAllocation($warehouseAllocationTransfer);
        }

        return $warehouseAllocationCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation $warehouseAllocationEntity
     * @param \Generated\Shared\Transfer\WarehouseAllocationTransfer $warehouseAllocationTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseAllocationTransfer
     */
    public function mapWarehouseAllocationEntityToWarehouseAllocationTransfer(
        SpyWarehouseAllocation $warehouseAllocationEntity,
        WarehouseAllocationTransfer $warehouseAllocationTransfer
    ): WarehouseAllocationTransfer {
         return $warehouseAllocationTransfer
             ->fromArray($warehouseAllocationEntity->toArray(), true)
             ->setWarehouse(
                 (new StockTransfer())->fromArray($warehouseAllocationEntity->getWarehouse()->toArray(), true),
             );
    }
}
