<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Dependency\Facade;

use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilityGuiToStockInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProductTransfer $transferStockProduct);

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $stockProductTransfer);

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\StockProductTransfer>
     */
    public function findStockProductsByIdProductForStore($idProductConcrete, StoreTransfer $storeTransfer);

    /**
     * @return array<string[]>
     */
    public function getWarehouseToStoreMapping();

    /**
     * @return array<string[]>
     */
    public function getStoreToWarehouseMapping();

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<string>
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer);

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\StockTransfer>
     */
    public function getAvailableWarehousesForStore(StoreTransfer $storeTransfer): array;
}
