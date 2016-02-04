<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Price\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Orm\Zed\Price\Persistence\SpyPriceType;
use Orm\Zed\Price\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

class PriceQueryContainer extends AbstractQueryContainer
{

    const DATE_NOW = 'now';

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function queryPriceType($name)
    {
        return SpyPriceTypeQuery::create()->filterByName($name);
    }

    /**
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function queryAllPriceTypes()
    {
        return SpyPriceTypeQuery::create();
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductAbstract($sku, SpyPriceType $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByPriceType($priceType)
            ->useSpyProductAbstractQuery()
            ->filterBySku($sku)
            ->endUse();
    }

    /**
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductConcrete($sku, SpyPriceType $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByPriceType($priceType)
            ->useProductQuery()
            ->filterBySku($sku)
            ->endUse();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function querySpecificPriceForProductAbstract(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByPrice($transferPriceProduct->getPrice())
            ->filterByPriceType($priceType)
            ->useSpyProductAbstractQuery()
            ->filterBySku($transferPriceProduct->getSkuProduct())
            ->endUse();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function querySpecificPriceForProductConcrete(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByPrice($transferPriceProduct->getPrice())
            ->filterByPriceType($priceType)
            ->useProductQuery()
            ->filterBySku($transferPriceProduct->getSkuProduct())
            ->endUse();
    }

    /**
     * @param int $idPriceProduct
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductEntity($idPriceProduct)
    {
        return SpyPriceProductQuery::create()
            ->filterByIdPriceProduct($idPriceProduct);
    }

    /**
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceGrid()
    {
        return SpyPriceProductQuery::create()
            ->joinProduct()
            ->withColumn(SpyProductTableMap::COL_SKU, 'sku_product_concrete')
            ->joinSpyProductAbstract()
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, 'sku_product_abstract')
            ->joinPriceType()
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'price_type_name');
    }

    /**
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function queryPriceTypeGrid()
    {
        return SpyPriceTypeQuery::create()
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'name');
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryPriceTypeForm()
    {
        return SpyPriceTypeQuery::create()
            ->select([
                SpyPriceTypeTableMap::COL_NAME => 'value',
                SpyPriceTypeTableMap::COL_NAME => 'label',
            ])
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'value')
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'label');
    }

}
