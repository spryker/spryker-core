<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductPersistenceFactory getFactory()
 */
class PriceProductRepository extends AbstractRepository implements PriceProductRepositoryInterface
{
    public const PRICE_PRODUCT_RELATION_NAME = 'PriceProduct';

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findProductConcretePricesBySkuAndCriteria(
        string $concreteSku,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ObjectCollection {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);
        $this->addJoinProductConcreteBySku($priceProductStoreQuery, $concreteSku);

        return $priceProductStoreQuery->find();
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findProductAbstractPricesBySkuAndCriteria(
        string $abstractSku,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ObjectCollection {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);
        $this->addJoinProductAbstractBySku($priceProductStoreQuery, $abstractSku);

        return $priceProductStoreQuery->find();
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findProductConcretePricesByIdAndCriteria(
        int $idProductConcrete,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ObjectCollection {
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
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findProductAbstractPricesByIdAndCriteria(
        int $idProductAbstract,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ObjectCollection {
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
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findProductAbstractPricesByIdIn(array $productAbstractIds): ObjectCollection
    {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery(new PriceProductCriteriaTransfer());

        $priceProductStoreQuery
            ->innerJoinWithPriceProduct()
            ->usePriceProductQuery()
                ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse();

        return $priceProductStoreQuery->find();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildDefaultPriceDimensionQueryCriteria(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?QueryCriteriaTransfer
    {
        return $this->getFactory()
            ->createDefaultPriceQueryExpander()
            ->buildDefaultPriceDimensionQueryCriteria($priceProductCriteriaTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildUnconditionalDefaultPriceDimensionQueryCriteria(): QueryCriteriaTransfer
    {
        return $this->getFactory()
            ->createDefaultPriceQueryExpander()
            ->buildDefaultPriceDimensionQueryCriteria(new PriceProductCriteriaTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer[]
     */
    public function findOrphanPriceProductStoreEntities(): array
    {
        $priceProductStoreQuery = $this->getFactory()
            ->createPriceProductStoreQuery();

        $this->getFactory()
            ->createPriceProductDimensionQueryExpander()
            ->expandPriceProductStoreQueryWithPriceDimensionForDelete(
                $priceProductStoreQuery,
                new PriceProductCriteriaTransfer()
            );

        return $this->buildQueryFromCriteria($priceProductStoreQuery)->find();
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param string $concreteSku
     *
     * @return $this
     */
    protected function addJoinProductConcreteBySku(SpyPriceProductStoreQuery $priceProductStoreQuery, $concreteSku)
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
    protected function addJoinProductAbstractBySku(SpyPriceProductStoreQuery $priceProductStoreQuery, $abstractSku)
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

        if ($priceProductCriteriaTransfer->getPriceType()) {
            $priceProductStoreQuery
                ->usePriceProductQuery()
                    ->usePriceTypeQuery()
                        ->filterByName($priceProductCriteriaTransfer->getPriceType())
                    ->endUse()
                ->endUse();
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

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    public function findIdPriceProductForProductConcrete(PriceProductTransfer $priceProductTransfer): ?int
    {
        $priceProductEntity = $this->getFactory()
            ->createPriceProductQuery()
            ->filterByFkProduct($priceProductTransfer->getIdProduct())
            ->filterByFkPriceType($priceProductTransfer->getFkPriceType())
            ->findOne();

        if ($priceProductEntity !== null) {
            return $priceProductEntity->getIdPriceProduct();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    public function findIdPriceProductForProductAbstract(PriceProductTransfer $priceProductTransfer): ?int
    {
        $priceProductEntity = $this->getFactory()
            ->createPriceProductQuery()
            ->filterByFkProductAbstract($priceProductTransfer->getIdProductAbstract())
            ->filterByFkPriceType($priceProductTransfer->getFkPriceType())
            ->findOne();

        if ($priceProductEntity !== null) {
            return $priceProductEntity->getIdPriceProduct();
        }

        return null;
    }

    /**
     * @param int[] $productAbstractIds
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findProductAbstractPricesByIdInAndCriteria(array $productAbstractIds, ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null): ObjectCollection
    {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);

        $priceProductStoreQuery
            ->innerJoinWithPriceProduct()
            ->usePriceProductQuery()
                ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse();

        return $priceProductStoreQuery->find();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    public function findIdPriceProductStoreByPriceProduct(PriceProductTransfer $priceProductTransfer): ?int
    {
        $priceProductTransfer->requireMoneyValue();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
        $moneyValueTransfer->requireCurrency();

        $priceProductStoreEntity = $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->filterByFkCurrency($moneyValueTransfer->getCurrency()->getIdCurrency())
            ->filterByFkStore($moneyValueTransfer->getFkStore())
            ->findOne();

        if ($priceProductStoreEntity !== null) {
            return (int)$priceProductStoreEntity->getIdPriceProductStore();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function isPriceProductUsedForOtherCurrencyAndStore(PriceProductTransfer $priceProductTransfer): bool
    {
        $priceProductTransfer->requireIdPriceProduct()
            ->requireMoneyValue();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
        $moneyValueTransfer->requireCurrency();

        $priceProductStoreEntityQuery = $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->filterByFkCurrency($moneyValueTransfer->getCurrency()->getIdCurrency(), Criteria::NOT_EQUAL)
            ->_or()
            ->filterByFkStore($moneyValueTransfer->getFkStore(), Criteria::NOT_EQUAL);

        return $this->buildQueryFromCriteria($priceProductStoreEntityQuery)->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer[]
     */
    public function findPriceProductStoresByPriceProduct(PriceProductTransfer $priceProductTransfer): array
    {
        $priceProductTransfer->requireMoneyValue();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
        $moneyValueTransfer->requireCurrency();

        $priceProductStoreEntityQuery = $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->filterByFkCurrency($moneyValueTransfer->getCurrency()->getIdCurrency())
            ->filterByFkStore($moneyValueTransfer->getFkStore());

        return $this->buildQueryFromCriteria($priceProductStoreEntityQuery)->find();
    }

    /**
     * @param string[] $concreteSkus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getProductAbstractPricesByConcreteSkusAndCriteria(
        array $concreteSkus,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);
        $priceProductStoreEntities = $priceProductStoreQuery
            ->joinWithCurrency()
            ->addAsColumn('product_sku', 'spy_product.sku')
            ->innerJoinWithPriceProduct()
            ->usePriceProductQuery()
                ->innerJoinWithSpyProductAbstract()
                ->useSpyProductAbstractQuery()
                    ->innerJoinWithSpyProduct()
                    ->useSpyProductQuery()
                        ->filterBySku_In($concreteSkus)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->find();

        if (!$priceProductStoreEntities->count()) {
            return [];
        }

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities, $concreteSkus);
    }

    /**
     * @param string[] $concreteSkus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getProductConcretePricesByConcreteSkusAndCriteria(
        array $concreteSkus,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);
        $priceProductStoreEntities = $priceProductStoreQuery
            ->joinWithCurrency()
            ->joinWithPriceProduct()
            ->usePriceProductQuery()
                ->innerJoinWithProduct()
                ->useProductQuery()
                    ->filterBySku_In($concreteSkus)
                ->endUse()
            ->endUse()
            ->find();

        if (!$priceProductStoreEntities->count()) {
            return [];
        }

        return $this->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[] $priceProductStoreEntities
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function mapPriceProductStoreEntitiesToPriceProductTransfers(ObjectCollection $priceProductStoreEntities): array
    {
        $mapper = $this->getFactory()->createPriceProductMapper();
        $priceProductTransfers = [];
        foreach ($priceProductStoreEntities as $priceProductStoreEntity) {
            $priceProductTransfers[] = $mapper->mapPriceProductStoreEntityToPriceProductTransfer($priceProductStoreEntity, new PriceProductTransfer());
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function isPriceProductByProductIdentifierAndPriceTypeExists(PriceProductTransfer $priceProductTransfer): bool
    {
        return $this->getFactory()
            ->createPriceProductQuery()
            ->filterByFkProductAbstract($priceProductTransfer->getIdProductAbstract())
            ->filterByFkProduct($priceProductTransfer->getIdProduct())
            ->filterByFkPriceType($priceProductTransfer->getFkPriceType())->count() > 0;
    }
}
