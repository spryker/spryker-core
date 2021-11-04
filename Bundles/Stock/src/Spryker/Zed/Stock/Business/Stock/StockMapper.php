<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class StockMapper implements StockMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\StockTransfer> $stockTransfers
     *
     * @return array<array<string>>
     */
    public function mapStoresToWarehouses(array $stockTransfers): array
    {
        $mapping = [];
        foreach ($stockTransfers as $stockTransfer) {
            $mapping[$stockTransfer->getName()] = $this->getStoreNamesFromStoreRelation($stockTransfer->getStoreRelation());
        }

        return $mapping;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StockTransfer> $stockTransfers
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<array<string>>
     */
    public function mapWarehousesToStores(array $stockTransfers, array $storeTransfers): array
    {
        $mapping = array_fill_keys($this->getStoreNamesFromStoreTransferCollection($storeTransfers), []);
        foreach ($stockTransfers as $stockTransfer) {
            $mapping = $this->mapStockToStores($stockTransfer, $storeTransfers, $mapping);
        }

        return $mapping;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<string>
     */
    protected function getStoreNamesFromStoreTransferCollection(array $storeTransfers): array
    {
        return array_map(function (StoreTransfer $storeTransfer): string {
            return $storeTransfer->getName();
        }, $storeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     * @param array<array<string>> $storeStockMapping
     *
     * @return array<array<string>>
     */
    protected function mapStockToStores(StockTransfer $stockTransfer, array $storeTransfers, array $storeStockMapping): array
    {
        $relatedStoreNames = $this->getStoreNamesFromStoreRelation($stockTransfer->getStoreRelation());
        foreach ($storeTransfers as $storeTransfer) {
            if (in_array($storeTransfer->getName(), $relatedStoreNames, true)) {
                $storeStockMapping[$storeTransfer->getName()][] = $stockTransfer->getName();
            }
        }

        return $storeStockMapping;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return array<string>
     */
    protected function getStoreNamesFromStoreRelation(StoreRelationTransfer $storeRelationTransfer): array
    {
        $storeNames = [];
        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeName = $storeTransfer->getName();
            $storeNames[$storeName] = $storeName;
        }

        return $storeNames;
    }
}
