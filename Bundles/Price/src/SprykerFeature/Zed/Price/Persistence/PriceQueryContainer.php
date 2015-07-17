<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Price\Persistence\Propel\Map\SpyPriceTypeTableMap;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceProductQuery;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceType;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceTypeQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;

class PriceQueryContainer extends AbstractQueryContainer
{

    const DATE_NOW = 'now';

    /**
     * @param string $name
     *
     * @return SpyPriceTypeQuery
     */
    public function queryPriceType($name)
    {
        return SpyPriceTypeQuery::create()->filterByName($name);
    }

    /**
     * @return SpyPriceTypeQuery
     */
    public function queryAllPriceTypes()
    {
        return SpyPriceTypeQuery::create();
    }

    /**
     * @param string $sku
     * @param SpyPriceType $priceType
     *
     * @throws PropelException
     *
     * @return Propel\SpyPriceProductQuery
     */
    public function queryPriceEntityForAbstractProduct($sku, SpyPriceType $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByPriceType($priceType)
            ->useSpyAbstractProductQuery()
            ->filterBySku($sku)
            ->endUse()
            ;
    }

    /**
     * @param string $sku
     * @param SpyPriceType $priceType
     *
     * @throws PropelException
     *
     * @return Propel\SpyPriceProductQuery
     */
    public function queryPriceEntityForConcreteProduct($sku, SpyPriceType $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByPriceType($priceType)
            ->useProductQuery()
            ->filterBySku($sku)
            ->endUse()
            ;
    }

    /**
     * @param SpyPriceProductQuery $query
     * @param int $idPriceProduct
     *
     * @return SpyPriceProductQuery
     */
    public function addFilter($query, $idPriceProduct)
    {
        return $query->filterByIdPriceProduct($idPriceProduct, Criteria::NOT_EQUAL);
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     * @param SpyPriceType $priceType
     *
     * @throws PropelException
     *
     * @return Propel\SpyPriceProductQuery
     */
    public function querySpecificPriceForAbstractProduct(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByPrice($transferPriceProduct->getPrice())
            ->filterByPriceType($priceType)
            ->useSpyAbstractProductQuery()
            ->filterBySku($transferPriceProduct->getSkuProduct())
            ->endUse()
            ;
    }

    /**
     * @param PriceProductTransfer $transferPriceProduct
     * @param SpyPriceType $priceType
     *
     * @throws PropelException
     *
     * @return SpyPriceProductQuery
     */
    public function querySpecificPriceForConcreteProduct(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType)
    {
        return SpyPriceProductQuery::create()
            ->filterByPrice($transferPriceProduct->getPrice())
            ->filterByPriceType($priceType)
            ->useProductQuery()
            ->filterBySku($transferPriceProduct->getSkuProduct())
            ->endUse()
            ;
    }

    /**
     * @param int $idPriceProduct
     *
     * @return SpyPriceProductQuery

     */
    public function queryPriceProductEntity($idPriceProduct)
    {
        return SpyPriceProductQuery::create()
            ->filterByIdPriceProduct($idPriceProduct)
            ;
    }

    /**
     * @return SpyPriceProductQuery
     */
    public function queryPriceGrid()
    {
        return SpyPriceProductQuery::create()
            ->joinProduct()
            ->withColumn(SpyProductTableMap::COL_SKU, 'sku_product_concrete')
            ->joinSpyAbstractProduct()
            ->withColumn(SpyAbstractProductTableMap::COL_SKU, 'sku_product_abstract')
            ->joinPriceType()
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'price_type_name')
            ;
    }

    /**
     * @return SpyPriceTypeQuery
     */
    public function queryPriceTypeGrid()
    {
        return SpyPriceTypeQuery::create()
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'name');
    }

    /**
     * @throws PropelException
     *
     * @return ModelCriteria
     */
    public function queryPriceTypeForm()
    {
        return SpyPriceTypeQuery::create()
            ->select([
                SpyPriceTypeTableMap::COL_NAME => 'value',
                SpyPriceTypeTableMap::COL_NAME => 'label',
            ])
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'value')
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'label')
            ;
    }

}
