<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Persistence;

use Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer;
use Generated\Shared\Transfer\WarehouseAllocationConditionsTransfer;
use Generated\Shared\Transfer\WarehouseAllocationCriteriaTransfer;
use Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationPersistenceFactory getFactory()
 */
class WarehouseAllocationRepository extends AbstractRepository implements WarehouseAllocationRepositoryInterface
{
    /**
     * @uses Stock
     *
     * @param \Generated\Shared\Transfer\WarehouseAllocationCriteriaTransfer $warehouseAllocationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer
     */
    public function getWarehouseAllocationCollection(
        WarehouseAllocationCriteriaTransfer $warehouseAllocationCriteriaTransfer
    ): WarehouseAllocationCollectionTransfer {
        $warehouseAllocationQuery = $this->getFactory()
            ->createWarehouseAllocationPropelQuery()
            ->joinWithWarehouse();

        if ($warehouseAllocationCriteriaTransfer->getWarehouseAllocationConditions()) {
            $warehouseAllocationQuery = $this->applyFilters(
                $warehouseAllocationCriteriaTransfer->getWarehouseAllocationConditionsOrFail(),
                $warehouseAllocationQuery,
            );
        }

        $warehouseAllocationEntities = $warehouseAllocationQuery->find();

        $warehouseAllocationCollectionTransfer = new WarehouseAllocationCollectionTransfer();
        if ($warehouseAllocationEntities->count() === 0) {
            return $warehouseAllocationCollectionTransfer;
        }

        return $this->getFactory()
            ->createWarehouseAllocationMapper()
            ->mapWarehouseAllocationEntitiesToWarehouseAllocationCollectionTransfer(
                $warehouseAllocationEntities,
                $warehouseAllocationCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseAllocationConditionsTransfer $warehouseAllocationConditionsTransfer
     * @param \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocationQuery $warehouseAllocationQuery
     *
     * @return \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocationQuery
     */
    protected function applyFilters(
        WarehouseAllocationConditionsTransfer $warehouseAllocationConditionsTransfer,
        SpyWarehouseAllocationQuery $warehouseAllocationQuery
    ): SpyWarehouseAllocationQuery {
        if ($warehouseAllocationConditionsTransfer->getWarehouseIds()) {
            $warehouseAllocationQuery->filterByFkWarehouse_In($warehouseAllocationConditionsTransfer->getWarehouseIds());
        }

        if ($warehouseAllocationConditionsTransfer->getSalesOrderItemUuids()) {
            $warehouseAllocationQuery->filterBySalesOrderItemUuid_In(
                $warehouseAllocationConditionsTransfer->getSalesOrderItemUuids(),
            );
        }

        return $warehouseAllocationQuery;
    }
}
