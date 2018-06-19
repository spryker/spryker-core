<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductPersistenceFactory getFactory()
 */
class PriceProductRepository extends AbstractRepository implements PriceProductRepositoryInterface
{
    public const PRICE_PRODUCT_RELATION_NAME = 'PriceProduct';

    /**
     * @api
     *
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]
     */
    public function findProductConcretePricesBySkuAndCriteria(
        $concreteSku,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ) {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);
        $this->addJoinProductConcreteBySku($priceProductStoreQuery, $concreteSku);

        return $priceProductStoreQuery->find();
    }

    /**
     * @api
     *
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]
     */
    public function findProductAbstractPricesBySkuAndCriteria(
        $abstractSku,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ) {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);
        $this->addJoinProductAbstractBySku($priceProductStoreQuery, $abstractSku);

        return $priceProductStoreQuery->find();
    }

    /**
     * @api
     *
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]
     */
    public function findProductConcretePricesByIdAndCriteria(
        $idProductConcrete,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ) {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer)
            ->joinWith(static::PRICE_PRODUCT_RELATION_NAME)
            ->addJoinCondition(
                static::PRICE_PRODUCT_RELATION_NAME,
                SpyPriceProductTableMap::COL_FK_PRODUCT . ' = ?',
                $idProductConcrete
            );

        return $priceProductStoreQuery->find();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPricesByIdAndCriteria(
        $idProductAbstract,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ) {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer)
            ->joinWith(static::PRICE_PRODUCT_RELATION_NAME)
            ->addJoinCondition(
                static::PRICE_PRODUCT_RELATION_NAME,
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT . ' = ?',
                $idProductAbstract
            );

        return $priceProductStoreQuery->find();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildDefaultPriceDimensionCriteria(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?QueryCriteriaTransfer
    {
        return $this->getFactory()
            ->createDefaultPriceQueryExpander()
            ->buildDefaultPriceDimensionCriteria($priceProductCriteriaTransfer);
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param string $concreteSku
     *
     * @return $this
     */
    protected function addJoinProductConcreteBySku(SpyPriceProductStoreQuery $priceProductStoreQuery, $concreteSku): PriceProductRepository
    {
        $priceProductStoreQuery
            ->joinWithPriceProduct()
            ->addJoin([
                SpyPriceProductTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_SKU,
            ], [
                SpyProductTableMap::COL_ID_PRODUCT,
                Propel::getConnection()->quote($concreteSku),
            ]);

        return $this;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param string $abstractSku
     *
     * @return $this
     */
    protected function addJoinProductAbstractBySku(SpyPriceProductStoreQuery $priceProductStoreQuery, $abstractSku): PriceProductRepository
    {
        $priceProductStoreQuery
            ->joinWithPriceProduct()
            ->addJoin([
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_SKU,
            ], [
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                Propel::getConnection()->quote($abstractSku),
            ]);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    protected function createBasePriceProductStoreQuery(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): SpyPriceProductStoreQuery
    {
        $priceProductStoreQuery = $this->getFactory()
            ->createPriceProductStoreQuery();

        if ($priceProductCriteriaTransfer->getIdStore()) {
            $priceProductStoreQuery->filterByFkStore($priceProductCriteriaTransfer->getIdStore());
        }

        if ($priceProductCriteriaTransfer->getIdCurrency()) {
            $priceProductStoreQuery->filterByFkCurrency($priceProductCriteriaTransfer->getIdCurrency());
        }

        $this->getFactory()
            ->createPriceProductDimensionQueryExpander()
            ->expandPriceProductStoreQueryWithPriceDimension($priceProductStoreQuery, $priceProductCriteriaTransfer);

        return $priceProductStoreQuery;
    }

    /**
     * @param int $idPriceProductStore
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer|null
     */
    public function findPriceProductDefaultByIdPriceProductStore(int $idPriceProductStore): ?SpyPriceProductDefaultEntityTransfer
    {
        $priceProductDefaultQuery = $this->getFactory()
            ->createPriceProductDefaultQuery()
            ->filterByFkPriceProductStore($idPriceProductStore);

        return $this->buildQueryFromCriteria($priceProductDefaultQuery)->findOne();
    }
}
