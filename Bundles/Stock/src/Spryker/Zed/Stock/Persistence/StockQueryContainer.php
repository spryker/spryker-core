<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;

/**
 * @method StockPersistenceFactory getFactory()
 */
class StockQueryContainer extends AbstractQueryContainer
{

    /**
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByNeverOutOfStockAllTypes($idProduct)
    {
        return $this->getFactory()->createStockProductQuery()
            ->filterByIsNeverOutOfStock(true)
            ->filterByFkProduct($idProduct);
    }

    /**
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByProducts($idProduct)
    {
        return $this->getFactory()->createStockProductQuery()
            ->filterByFkProduct($idProduct);
    }

    /**
     * @param int $idStock
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockProductByStockAndProduct($idStock, $idProduct)
    {
        return $this->getFactory()->createStockProductQuery()
            ->filterByFkStock($idStock)
            ->filterByFkProduct($idProduct);
    }

    /**
     * @param string $sku
     * @param string $type
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
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
            ->endUse();

        return $query;
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function queryStockByName($name)
    {
        return $this->getFactory()->createStockQuery()
            ->filterByName($name);
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function queryAllStockTypes()
    {
        return $this->getFactory()->createStockQuery();
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryAllStockProducts()
    {
        return $this->getFactory()->createStockProductQuery();
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryAllStockProductsJoinedStockJoinedProduct()
    {
        $query = $this->getFactory()->createStockProductQuery()
            ->withColumn(SpyStockProductTableMap::COL_ID_STOCK_PRODUCT, 'id_stock_product')
            ->withColumn(SpyProductTableMap::COL_SKU, 'sku')
            ->withColumn(SpyStockTableMap::COL_ID_STOCK, 'id_stock')
            ->withColumn(SpyStockTableMap::COL_NAME, 'name')
            ->joinStock()
            ->joinSpyProduct();

        return $query;
    }

    /**
     * @param int $idStockProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockProductByIdStockProduct($idStockProduct)
    {
        return $this->getFactory()->createStockProductQuery()
            ->filterByIdStockProduct($idStockProduct);
    }

}
