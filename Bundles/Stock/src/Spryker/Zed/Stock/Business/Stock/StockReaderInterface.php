<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use Generated\Shared\Transfer\StoreTransfer;

interface StockReaderInterface
{
    /**
     * @return string[]
     */
    public function getStockTypes(): array;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string[]
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getAvailableWarehousesForStore(StoreTransfer $storeTransfer): array;

    /**
     * @return string[][]
     */
    public function getWarehouseToStoreMapping(): array;

    /**
     * @return string[][]
     */
    public function getStoreToWarehouseMapping(): array;

    /**
     * @param string $stockType
     *
     * @return int
     */
    public function getStockTypeIdByName(string $stockType): int;
}
