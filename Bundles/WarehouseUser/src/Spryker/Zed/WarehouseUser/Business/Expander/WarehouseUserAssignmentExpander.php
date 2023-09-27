<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Spryker\Zed\WarehouseUser\Business\Reader\WarehouseReaderInterface;

class WarehouseUserAssignmentExpander implements WarehouseUserAssignmentExpanderInterface
{
    /**
     * @var \Spryker\Zed\WarehouseUser\Business\Reader\WarehouseReaderInterface
     */
    protected WarehouseReaderInterface $warehouseReader;

    /**
     * @param \Spryker\Zed\WarehouseUser\Business\Reader\WarehouseReaderInterface $warehouseReader
     */
    public function __construct(WarehouseReaderInterface $warehouseReader)
    {
        $this->warehouseReader = $warehouseReader;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>
     */
    public function expandWarehouseUserAssignmentTransfersWithWarehouses(ArrayObject $warehouseUserAssignmentTransfers): ArrayObject
    {
        $stockCollectionTransfer = $this->warehouseReader->getStockCollection($warehouseUserAssignmentTransfers);

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
