<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence;

use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StockRepositoryInterface
{
    /**
     * @return string[]
     */
    public function getStockNames(): array;

    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getStockNamesForStore(string $storeName): array;

    /**
     * @param int $idStock
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockById(int $idStock): ?StockTransfer;

    /**
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getStocksWithRelatedStoresByCriteriaFilter(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): array;

    /**
     * @param string $stockName
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockByName(string $stockName): ?StockTransfer;

    /**
     * @param int $idStock
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductsByIdStock(int $idStock): array;

    /**
     * @param int $idStock
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationByIdStock(int $idStock): StoreRelationTransfer;

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWhereProductStockIsDefined(string $sku): array;

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductByProductAbstractSkuForStore(string $abstractSku, StoreTransfer $storeTransfer): array;

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductAbstractNeverOutOfStockForStore(string $abstractSku, StoreTransfer $storeTransfer): bool;

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function findProductStocksForStore(string $concreteSku, StoreTransfer $storeTransfer): array;

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductsByProductConcreteSku(string $concreteSku): array;
}
