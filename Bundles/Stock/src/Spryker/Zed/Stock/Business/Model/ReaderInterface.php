<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ReaderInterface
{
    /**
     * @return array
     */
    public function getStockTypes();

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    public function getStockTypesForStore(StoreTransfer $storeTransfer);

    /**
     * @return array
     */
    public function getWarehouseToStoreMapping();

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isNeverOutOfStockForStore($sku, StoreTransfer $storeTransfer);

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct[]
     */
    public function getStocksProduct($sku);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct[]
     */
    public function findProductStocksForStore($sku, StoreTransfer $storeTransfer);

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @return bool
     */
    public function hasStockProduct($sku, $stockType);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function hastStockProductInStore($sku, StoreTransfer $storeTransfer);

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @return int
     */
    public function getIdStockProduct($sku, $stockType);

    /**
     * @param string $stockType
     *
     * @return int
     */
    public function getStockTypeIdByName($stockType);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function findProductAbstractIdBySku($sku);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku);

    /**
     * @param int $idStockType
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductAlreadyExistsException
     *
     * @return void
     */
    public function checkStockDoesNotExist($idStockType, $idProduct);

    /**
     * @param int $idStockProduct
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    public function getStockProductById($idStockProduct);

    /**
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Stock\Business\Exception\StockProductNotFoundException
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function getStockProductsByIdProduct($idProductConcrete);

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]|null
     */
    public function findStockProductsByIdProductForStore($idProductConcrete, StoreTransfer $storeTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithStocks(ProductConcreteTransfer $productConcreteTransfer);
}
