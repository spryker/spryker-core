<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Business\Expander;

use Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer;
use Generated\Shared\Transfer\WarehouseAllocationConditionsTransfer;
use Generated\Shared\Transfer\WarehouseAllocationCriteriaTransfer;
use Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationRepositoryInterface;

class WarehouseExpander implements WarehouseExpanderInterface
{
    /**
     * @var \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationRepositoryInterface
     */
    protected WarehouseAllocationRepositoryInterface $warehouseAllocationRepository;

    /**
     * @param \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationRepositoryInterface $warehouseAllocationRepository
     */
    public function __construct(WarehouseAllocationRepositoryInterface $warehouseAllocationRepository)
    {
        $this->warehouseAllocationRepository = $warehouseAllocationRepository;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithWarehouse(array $itemTransfers): array
    {
        $itemUuids = $this->extractUuidsFromItems($itemTransfers);

        $warehouseAllocationCollectionTransfer = $this->warehouseAllocationRepository->getWarehouseAllocationCollection(
            $this->createWarehouseAllocationCriteriaTransfer($itemUuids),
        );

        if (!$warehouseAllocationCollectionTransfer->getWarehouseAllocations()->count()) {
            return $itemTransfers;
        }

        $stockTransfersIndexedByOrderItemUuids = $this->getStockTransfersIndexedByOrderItemUuids($warehouseAllocationCollectionTransfer);

        foreach ($itemTransfers as $itemTransfer) {
            if (isset($stockTransfersIndexedByOrderItemUuids[$itemTransfer->getUuidOrFail()])) {
                $itemTransfer->setWarehouse(
                    $stockTransfersIndexedByOrderItemUuids[$itemTransfer->getUuidOrFail()],
                );
            }
        }

        return $itemTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<string>
     */
    protected function extractUuidsFromItems(array $itemTransfers): array
    {
        $itemUuids = [];
        foreach ($itemTransfers as $itemTransfer) {
            $itemUuids[] = $itemTransfer->getUuidOrFail();
        }

        return $itemUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer $warehouseAllocationCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\StockTransfer>
     */
    protected function getStockTransfersIndexedByOrderItemUuids(
        WarehouseAllocationCollectionTransfer $warehouseAllocationCollectionTransfer
    ): array {
        $stockTransfersIndexedByOrderItemUuids = [];

        foreach ($warehouseAllocationCollectionTransfer->getWarehouseAllocations() as $warehouseAllocationTransfer) {
            $stockTransfersIndexedByOrderItemUuids[$warehouseAllocationTransfer->getSalesOrderItemUuidOrFail()] = $warehouseAllocationTransfer->getWarehouseOrFail();
        }

        return $stockTransfersIndexedByOrderItemUuids;
    }

    /**
     * @param array<string> $salesOrderItemUuids
     *
     * @return \Generated\Shared\Transfer\WarehouseAllocationCriteriaTransfer
     */
    protected function createWarehouseAllocationCriteriaTransfer(array $salesOrderItemUuids): WarehouseAllocationCriteriaTransfer
    {
        $warehouseAllocationConditionsTransfer = (new WarehouseAllocationConditionsTransfer())->setSalesOrderItemUuids($salesOrderItemUuids);

        return (new WarehouseAllocationCriteriaTransfer())->setWarehouseAllocationConditions($warehouseAllocationConditionsTransfer);
    }
}
