<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;

class StockQueryContainer extends AbstractQueryContainer
{

    /**
     * @param int $idProduct
     *
     * @return SpyStockProductQuery
     */
    public function queryStockByNeverOutOfStockAllTypes($idProduct)
    {
        return SpyStockProductQuery::create()
            ->filterByIsNeverOutOfStock(true)
            ->filterByFkProduct($idProduct)
            ;
    }

    /**
     * @param int $idProduct
     *
     * @return SpyStockProductQuery
     */
    public function queryStockByProducts($idProduct)
    {
        return SpyStockProductQuery::create()
            ->filterByFkProduct($idProduct)
            ;
    }

    /**
     * @param int $idStock
     * @param int $idProduct
     *
     * @return SpyStockProductQuery
     */
    public function queryStockProductByStockAndProduct($idStock, $idProduct)
    {
        return SpyStockProductQuery::create()
            ->filterByFkStock($idStock)
            ->filterByFkProduct($idProduct)
            ;
    }

    /**
     * @param string $sku
     * @param string $type
     *
     * @return SpyStockProductQuery
     */
    public function queryStockProductBySkuAndType($sku, $type)
    {
        $query = $this->queryAllStockProducts();
        $query
            ->useSpyProductQuery()
            ->filterBySku($sku)
            ->endUse()
            ->useStockQuery()
            ->filterByName($type)
            ->endUse()
        ;

        return $query;
    }

    /**
     * @param string $name
     *
     * @return SpyStockQuery
     */
    public function queryStockByName($name)
    {
        return SpyStockQuery::create()
            ->filterByName($name)
            ;
    }

    /**
     * @return SpyStockQuery
     */
    public function queryAllStockTypes()
    {
        return SpyStockQuery::create();
    }

    /**
     * @return SpyStockProductQuery
     */
    public function queryAllStockProducts()
    {
        return SpyStockProductQuery::create();
    }

    /**
     * @return SpyStockProductQuery
     */
    public function queryAllStockProductsJoinedStockJoinedProduct()
    {
        $query = SpyStockProductQuery::create()
            ->withColumn(SpyStockProductTableMap::COL_ID_STOCK_PRODUCT, 'id_stock_product')
            ->withColumn(SpyProductTableMap::COL_SKU, 'sku')
            ->withColumn(SpyStockTableMap::COL_ID_STOCK, 'id_stock')
            ->withColumn(SpyStockTableMap::COL_NAME, 'name')
            ->joinStock()
            ->joinSpyProduct()
        ;

        return $query;
    }

    /**
     * @param int $idStockProduct
     *
     * @return SpyStockProductQuery
     */
    public function queryStockProductByIdStockProduct($idStockProduct)
    {
        return SpyStockProductQuery::create()
            ->filterByIdStockProduct($idStockProduct)
            ;
    }

}
