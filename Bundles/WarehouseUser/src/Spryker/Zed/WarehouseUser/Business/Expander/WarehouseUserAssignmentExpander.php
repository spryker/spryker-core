<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface;

class WarehouseUserAssignmentExpander implements WarehouseUserAssignmentExpanderInterface
{
    /**
     * @var \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface
     */
    protected WarehouseUserToStockFacadeInterface $stockFacade;

    /**
     * @param \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface $stockFacade
     */
    public function __construct(WarehouseUserToStockFacadeInterface $stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>
     */
    public function expandWarehouseUserAssignmentTransfersWithWarehouses(ArrayObject $warehouseUserAssignmentTransfers): ArrayObject
    {
        $stockCriteriaFilterTransfer = $this->createStockCriteriaFilterTransfer($warehouseUserAssignmentTransfers);
        $stockCollectionTransfer = $this->stockFacade->getStocksByStockCriteriaFilter($stockCriteriaFilterTransfer);

        $indexedStockTransfers = $this->getStockTransfersIndexedByUuid($stockCollectionTransfer);
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if (!$warehouseUserAssignmentTransfer->getWarehouse() || $warehouseUserAssignmentTransfer->getWarehouseOrFail()->getIdStock()) {
                continue;
            }

            $stockTransfer = $warehouseUserAssignmentTransfer->getWarehouseOrFail();
            if ($stockTransfer->getUuid() && isset($indexedStockTransfers[$stockTransfer->getUuidOrFail()])) {
                $warehouseUserAssignmentTransfer->setWarehouse($indexedStockTransfers[$stockTransfer->getUuidOrFail()]);
            }
        }

        return $warehouseUserAssignmentTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \Generated\Shared\Transfer\StockCriteriaFilterTransfer
     */
    protected function createStockCriteriaFilterTransfer(ArrayObject $warehouseUserAssignmentTransfers): StockCriteriaFilterTransfer
    {
        $stockCriteriaFilterTransfer = new StockCriteriaFilterTransfer();
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if (!$warehouseUserAssignmentTransfer->getWarehouse() || $warehouseUserAssignmentTransfer->getWarehouseOrFail()->getIdStock()) {
                continue;
            }

            $stockTransfer = $warehouseUserAssignmentTransfer->getWarehouseOrFail();
            if ($stockTransfer->getUuid()) {
                $stockCriteriaFilterTransfer->addUuid($stockTransfer->getUuidOrFail());
            }
        }

        return $stockCriteriaFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\StockTransfer>
     */
    protected function getStockTransfersIndexedByUuid(StockCollectionTransfer $stockCollectionTransfer): array
    {
        $indexedStockTransfers = [];
        foreach ($stockCollectionTransfer->getStocks() as $stockTransfer) {
            $indexedStockTransfers[$stockTransfer->getUuidOrFail()] = $stockTransfer;
        }

        return $indexedStockTransfers;
    }
}
