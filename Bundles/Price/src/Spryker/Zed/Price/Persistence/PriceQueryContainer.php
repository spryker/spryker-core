<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Price\Persistence\SpyPriceType;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Price\Persistence\PricePersistenceFactory getFactory()
 */
class PriceQueryContainer extends AbstractQueryContainer implements PriceQueryContainerInterface
{

    const DATE_NOW = 'now';

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function queryPriceType($name)
    {
        return $this->getFactory()->createPriceTypeQuery()->filterByName($name);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function queryAllPriceTypes()
    {
        return $this->getFactory()->createPriceTypeQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryAllPriceProducts()
    {
        return $this->getFactory()->createPriceProductQuery();
    }

    /**
     * @api
     *
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductAbstract($sku, SpyPriceType $priceType)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByPriceType($priceType)
            ->addJoin([
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_SKU,
            ], [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                $this->getConnection()->quote($sku),
            ]);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractBySku($sku)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByPrice(null, Criteria::ISNOTNULL)
            ->joinWithPriceType()
            ->useSpyProductAbstractQuery()
                ->filterBySku($sku)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractById($idProductAbstract)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByPrice(null, Criteria::ISNOTNULL)
            ->filterByFkProductAbstract($idProductAbstract)
            ->joinWithPriceType();
    }

    /**
     * @api

     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProduct()
    {
        return $this->getFactory()->createPriceProductQuery();
    }

    /**
     * @api
     *
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductConcrete($sku, SpyPriceType $priceType)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByPriceType($priceType)
            ->addJoin([
                SpyPriceProductTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_SKU,
            ], [
                SpyProductTableMap::COL_ID_PRODUCT,
                $this->getConnection()->quote($sku),
            ]);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteBySku($sku)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByPrice(null, Criteria::ISNOTNULL)
            ->joinWithPriceType()
            ->useProductQuery()
                ->filterBySku($sku)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteById($idProductConcrete)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByPrice(null, Criteria::ISNOTNULL)
            ->filterByFkProduct($idProductConcrete)
            ->joinWithPriceType();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function querySpecificPriceForProductAbstract(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByPrice($transferPriceProduct->getPrice())
            ->filterByPriceType($priceType)
            ->useSpyProductAbstractQuery()
            ->filterBySku($transferPriceProduct->getSkuProduct())
            ->endUse();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function querySpecificPriceForProductConcrete(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByPrice($transferPriceProduct->getPrice())
            ->filterByPriceType($priceType)
            ->useProductQuery()
            ->filterBySku($transferPriceProduct->getSkuProduct())
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idPriceProduct
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductEntity($idPriceProduct)
    {
        return $this->getFactory()->createPriceProductQuery()
            ->filterByIdPriceProduct($idPriceProduct);
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryProductAbstractPriceByIdConcreteProduct($idProduct)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->useSpyProductAbstractQuery()
                ->useSpyProductQuery()
                    ->filterByIdProduct($idProduct)
                ->endUse()
            ->endUse();
    }

}
