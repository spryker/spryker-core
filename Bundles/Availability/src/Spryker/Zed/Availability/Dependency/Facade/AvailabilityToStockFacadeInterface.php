<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

use Generated\Shared\Transfer\StockStoreCollectionTransfer;
use Generated\Shared\Transfer\StockStoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface AvailabilityToStockFacadeInterface
{
    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductAbstractNeverOutOfStockForStore(string $abstractSku, StoreTransfer $storeTransfer): bool;

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateProductAbstractStockForStore(string $abstractSku, StoreTransfer $storeTransfer): Decimal;

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateProductStockForStore(string $sku, StoreTransfer $storeTransfer): Decimal;

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isNeverOutOfStockForStore($sku, StoreTransfer $storeTransfer);

    /**
     * @return array<array<string>>
     */
    public function getStoreToWarehouseMapping();

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $sku
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoresWhereProductStockIsDefined(string $sku): array;

    /**
     * @param \Generated\Shared\Transfer\StockStoreCriteriaTransfer $stockStoreCollectionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockStoreCollectionTransfer
     */
    public function getStockStoreCollection(StockStoreCriteriaTransfer $stockStoreCollectionCriteriaTransfer): StockStoreCollectionTransfer;
}
