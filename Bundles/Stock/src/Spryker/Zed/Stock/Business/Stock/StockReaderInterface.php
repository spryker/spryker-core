<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Generated\Shared\Transfer\StockCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StockReaderInterface
{
    /**
     * @return array<string>
     */
    public function getStockTypes(): array;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<string>
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\StockTransfer>
     */
    public function getAvailableWarehousesForStore(StoreTransfer $storeTransfer): array;

    /**
     * @return array<array<string>>
     */
    public function getWarehouseToStoreMapping(): array;

    /**
     * @return array<array<string>>
     */
    public function getStoreToWarehouseMapping(): array;

    /**
     * @param string $stockType
     *
     * @return int
     */
    public function getStockTypeIdByName(string $stockType): int;

    /**
     * @deprecated Use {@link getStockCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function getStocksByStockCriteriaFilter(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): StockCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\StockCriteriaTransfer $stockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function getStockCollection(StockCriteriaTransfer $stockCriteriaTransfer): StockCollectionTransfer;
}
