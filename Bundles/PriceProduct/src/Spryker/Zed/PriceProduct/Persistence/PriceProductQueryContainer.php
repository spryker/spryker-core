<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceType;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductPersistenceFactory getFactory()
 */
class PriceProductQueryContainer extends AbstractQueryContainer implements PriceProductQueryContainerInterface
{
    const DATE_NOW = 'now';

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    public function queryPriceType($name)
    {
        return $this->getFactory()->createPriceTypeQuery()->filterByName($name);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    public function queryAllPriceTypes()
    {
        return $this->getFactory()->createPriceTypeQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryAllPriceProducts()
    {
        return $this->getFactory()->createPriceProductQuery();
    }

    /**
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     *
     */
    public function queryPriceEntityForProductAbstract($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->usePriceTypeQuery()
                ->filterByName($priceProductCriteriaTransfer->getPriceType())
            ->endUse()
            ->addJoin([
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_SKU,
            ], [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                $this->getConnection()->quote($sku),
            ])
            ->addJoin([
                SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT,
                SpyPriceProductStoreTableMap::COL_FK_CURRENCY,
                SpyPriceProductStoreTableMap::COL_FK_STORE,
            ], [
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT,
                (int)$priceProductCriteriaTransfer->getIdCurrency(),
                (int)$priceProductCriteriaTransfer->getIdStore()
            ]);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractBySku($sku)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByPrice(null, Criteria::ISNOTNULL)
            ->joinWithPriceType()
            ->joinWithPriceProductStore()
            ->useSpyProductAbstractQuery()
                ->filterBySku($sku)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractById($idProductAbstract)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByPrice(null, Criteria::ISNOTNULL)
            ->filterByFkProductAbstract($idProductAbstract)
            ->joinWithPriceProductStore()
            ->joinWithPriceType();
    }

    /**
     * @api

     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProduct()
    {
        return $this->getFactory()->createPriceProductQuery();
    }

    /**
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     *
     */
    public function queryPriceEntityForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->usePriceTypeQuery()
               ->filterByName($priceProductCriteriaTransfer->getPriceType())
            ->endUse()
            ->addJoin([
                SpyPriceProductTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_SKU,
            ],[
                SpyProductTableMap::COL_ID_PRODUCT,
                $this->getConnection()->quote($sku),
            ])
            ->addJoin([
                SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT,
                SpyPriceProductStoreTableMap::COL_FK_CURRENCY,
                SpyPriceProductStoreTableMap::COL_FK_STORE,
            ],[
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT,
                (int)$priceProductCriteriaTransfer->getIdCurrency(),
                (int)$priceProductCriteriaTransfer->getIdStore()
            ]);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteBySku($sku)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByPrice(null, Criteria::ISNOTNULL)
            ->joinWithPriceType()
            ->joinWithPriceProductStore()
            ->useProductQuery()
                ->filterBySku($sku)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
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
     * @param int $idPriceProduct
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductEntity($idPriceProduct)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByIdPriceProduct($idPriceProduct);
    }
}
