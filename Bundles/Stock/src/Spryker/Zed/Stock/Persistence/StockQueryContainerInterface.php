<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface StockQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByNeverOutOfStockAllTypes($idProduct);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProduct
     * @param array $stockNames
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByNeverOutOfStockAllTypesForStockNames($idProduct, array $stockNames);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByProducts($idProduct);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProduct
     * @param array $stockNames
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByProductsForStockNames($idProduct, array $stockNames);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idStock
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockProductByStockAndProduct($idStock, $idProduct);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     * @param string $type
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockProductBySkuAndType($sku, $type);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     * @param array $types
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockProductBySkuAndTypes($sku, array $types);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function queryStockByName($name);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function queryAllStockTypes();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $names
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function queryStockByNames(array $names);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryAllStockProducts();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryAllStockProductsJoinedStockJoinedProduct();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idStockProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockProductByIdStockProduct($idStockProduct);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByIdProduct($idProduct);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProduct
     * @param array $types
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByIdProductAndTypes($idProduct, array $types);
}
