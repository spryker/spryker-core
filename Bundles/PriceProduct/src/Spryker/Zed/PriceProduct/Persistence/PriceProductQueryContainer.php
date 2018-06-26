<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use PDO;
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
                (int)$priceProductCriteriaTransfer->getIdStore(),
            ]);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
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
            ], [
                SpyProductTableMap::COL_ID_PRODUCT,
                $this->getConnection()->quote($sku),
            ])
            ->addJoin([
                SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT,
                SpyPriceProductStoreTableMap::COL_FK_CURRENCY,
                SpyPriceProductStoreTableMap::COL_FK_STORE,
            ], [
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT,
                (int)$priceProductCriteriaTransfer->getIdCurrency(),
                (int)$priceProductCriteriaTransfer->getIdStore(),
            ]);
    }

    /**
     * @api
     *
     * @param int $idAbstractProduct
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function queryPriceEntityForProductAbstractById(
        $idAbstractProduct,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ) {
        return $this->getFactory()
            ->createPriceProductStoreQuery()
            ->addJoin([
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT,
                SpyPriceProductStoreTableMap::COL_FK_CURRENCY,
                SpyPriceProductStoreTableMap::COL_FK_STORE,
            ], [
                SpyPriceProductTableMap::COL_ID_PRICE_PRODUCT,
                (int)$priceProductCriteriaTransfer->getIdCurrency(),
                (int)$priceProductCriteriaTransfer->getIdStore(),
            ])
            ->addJoin([
                SpyPriceProductTableMap::COL_FK_PRICE_TYPE,
                SpyPriceTypeTableMap::COL_NAME,
            ], [
                SpyPriceTypeTableMap::COL_ID_PRICE_TYPE,
                $this->getConnection()->quote($priceProductCriteriaTransfer->getPriceType()),
            ])
            ->where(SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT . ' = ?', $idAbstractProduct, PDO::PARAM_INT);
    }

    /**
     * @api
     *
     * @param int $idPriceProduct
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function queryPriceProductStoreByProductCurrencyStore($idPriceProduct, $idCurrency, $idStore)
    {
        return $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByFkPriceProduct($idPriceProduct)
            ->filterByFkCurrency($idCurrency)
            ->filterByFkStore($idStore);
    }

    /**
     * @api
     *
     * @param int $idPriceProductStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function queryPriceProductStoreById($idPriceProductStore)
    {
        return $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByIdPriceProductStore($idPriceProductStore);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractBySkuForStore($sku, $idStore)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByPrice(null, Criteria::ISNOTNULL)
            ->joinWithPriceType()
            ->joinWithPriceProductStore()
            ->joinWithPriceProductDefault()
            ->where(SpyPriceProductStoreTableMap::COL_FK_STORE . ' = ?', $idStore)
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
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteBySkuForStore($sku, $idStore)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByPrice(null, Criteria::ISNOTNULL)
            ->joinWithPriceType()
            ->joinWithPriceProductStore()
            ->joinWithPriceProductDefault()
            ->where(SpyPriceProductStoreTableMap::COL_FK_STORE . ' = ?', $idStore)
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

    /**
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idPriceType
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductForConcreteProductBy($idProductConcrete, $idPriceType)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkPriceType($idPriceType)
            ->filterByFkProductAbstract(null, Criteria::ISNULL);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idPriceType
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductForAbstractProduct($idProductAbstract, $idPriceType)
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkPriceType($idPriceType);
    }
}
