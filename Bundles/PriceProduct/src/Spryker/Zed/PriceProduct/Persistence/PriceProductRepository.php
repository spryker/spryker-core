<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;

/**
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductPersistenceFactory getFactory()
 */
class PriceProductRepository extends AbstractRepository implements PriceProductRepositoryInterface
{
    use InstancePoolingTrait;

    /**
     * @var string
     */
    public const PRICE_PRODUCT_RELATION_NAME = 'PriceProduct';

    /**
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductConcretePricesBySkuAndCriteria(
        string $concreteSku,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);
        $this->addJoinProductConcreteBySku($priceProductStoreQuery, $concreteSku);

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            $priceProductCriteriaTransfer,
            $priceProductStoreQuery,
        );

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
    }

    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesBySkuAndCriteria(
        string $abstractSku,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);
        $this->addJoinProductAbstractBySku($priceProductStoreQuery, $abstractSku);

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            $priceProductCriteriaTransfer,
            $priceProductStoreQuery,
        );

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductConcretePricesByIdAndCriteria(
        int $idProductConcrete,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer)
            ->joinWith(static::PRICE_PRODUCT_RELATION_NAME)
            ->addJoinCondition(
                static::PRICE_PRODUCT_RELATION_NAME,
                SpyPriceProductTableMap::COL_FK_PRODUCT . ' = ?',
                $idProductConcrete,
            );

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            $priceProductCriteriaTransfer,
            $priceProductStoreQuery,
        );

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
    }

    /**
     * @param array<int> $productConcreteIds
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductConcretePricesByIdsAndCriteria(
        array $productConcreteIds,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer)
            ->joinWith(static::PRICE_PRODUCT_RELATION_NAME)
            ->addJoinCondition(
                static::PRICE_PRODUCT_RELATION_NAME,
                SpyPriceProductTableMap::COL_FK_PRODUCT . ' IN ?',
                $productConcreteIds,
            );

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            $priceProductCriteriaTransfer,
            $priceProductStoreQuery,
        );

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesByIdAndCriteria(
        int $idProductAbstract,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer)
            ->joinWith(static::PRICE_PRODUCT_RELATION_NAME)
            ->addJoinCondition(
                static::PRICE_PRODUCT_RELATION_NAME,
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT . ' = ?',
                $idProductAbstract,
            );

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            $priceProductCriteriaTransfer,
            $priceProductStoreQuery,
        );

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesByIdIn(array $productAbstractIds): array
    {
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery(new PriceProductCriteriaTransfer())
            ->innerJoinWithPriceProduct()
            ->usePriceProductQuery()
                ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse();

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            new PriceProductCriteriaTransfer(),
            $priceProductStoreQuery,
        );

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
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
        /** @var \Generated\Shared\Transfer\QueryCriteriaTransfer $defaultPriceDimensionQueryCriteria */
        $defaultPriceDimensionQueryCriteria = $this->getFactory()
            ->createDefaultPriceQueryExpander()
            ->buildDefaultPriceDimensionQueryCriteria(new PriceProductCriteriaTransfer());

        return $defaultPriceDimensionQueryCriteria;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findPriceProductTransfersWithOrphanPriceProductStore(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        $priceProductStoreQuery = $this->getFactory()
            ->createPriceProductStoreQuery();

        $priceProductStoreQuery = $this->applyCriteria($priceProductStoreQuery, $priceProductCriteriaTransfer);

        $this->getFactory()
            ->createPriceProductDimensionQueryExpander()
            ->expandPriceProductStoreQueryWithPriceDimensionForDelete(
                $priceProductStoreQuery,
                $priceProductCriteriaTransfer,
            );

        $priceProductStoreEntities = $priceProductStoreQuery->find()->getArrayCopy();

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
    }

    /**
     * This method allows to execute query per dimension. It suppose to fix the issue when several prices,
     * that belongs to the different dimensions but have the same `idPriceProductStore`,
     * are queried within one DB request.
     *
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     *
     * @return array<\Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore>
     */
    protected function getPriceProductStoreEntitiesPerDimension(
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer,
        SpyPriceProductStoreQuery $priceProductStoreQuery
    ): array {
        $isInstancePoolingEnabled = $this->isInstancePoolingEnabled();
        $this->disableInstancePooling();

        if ($priceProductCriteriaTransfer->getPriceDimension()) {
            $this->getFactory()
                ->createPriceProductDimensionQueryExpander()
                ->expandPriceProductStoreQueryWithPriceDimension($priceProductStoreQuery, $priceProductCriteriaTransfer);

            $priceProductStoreEntities = $priceProductStoreQuery->find()->getArrayCopy();
            if ($isInstancePoolingEnabled) {
                $this->enableInstancePooling();
            }

            return $priceProductStoreEntities;
        }
        $priceProductStoreEntities = [];
        $priceDimensionQueryCriteriaPlugins = $this->getFactory()->getPriceDimensionQueryCriteriaPlugins();

        foreach ($priceDimensionQueryCriteriaPlugins as $priceDimensionQueryCriteriaPlugin) {
            $dimensionName = $priceDimensionQueryCriteriaPlugin->getDimensionName();
            $expandedPriceProductStoreQuery = $this->getFactory()
                ->createPriceProductDimensionQueryExpander()
                ->expandPriceProductStoreQueryWithPriceDimensionByDimensionName(
                    clone $priceProductStoreQuery,
                    $priceProductCriteriaTransfer,
                    $dimensionName,
                );

            if (!$expandedPriceProductStoreQuery) {
                continue;
            }

            $priceProductStoreEntities = array_merge($priceProductStoreEntities, $expandedPriceProductStoreQuery->find()->getArrayCopy());
        }

        if ($isInstancePoolingEnabled) {
            $this->enableInstancePooling();
        }

        return $priceProductStoreEntities;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    protected function applyCriteria(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): SpyPriceProductStoreQuery {
        if ($priceProductCriteriaTransfer->getIdProductConcrete()) {
            $priceProductStoreQuery->joinPriceProduct()
                ->add(
                    SpyPriceProductTableMap::COL_FK_PRODUCT,
                    $priceProductCriteriaTransfer->getIdProductConcrete(),
                    Criteria::EQUAL,
                );

            return $priceProductStoreQuery;
        }
        if ($priceProductCriteriaTransfer->getIdProductAbstract()) {
            $priceProductStoreQuery->joinPriceProduct()
                ->add(
                    SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                    $priceProductCriteriaTransfer->getIdProductAbstract(),
                    Criteria::EQUAL,
                );
        }

        return $priceProductStoreQuery;
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
        $priceProductStoreQuery = $this->getFactory()->createPriceProductStoreQuery();

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

        if ($priceProductCriteriaTransfer->getIdProductAbstract()) {
            $priceProductStoreQuery
                ->usePriceProductQuery()
                    ->filterByFkProductAbstract($priceProductCriteriaTransfer->getIdProductAbstract())
                ->endUse();
        }

        if ($priceProductCriteriaTransfer->getIdProductConcrete()) {
            $priceProductStoreQuery
                ->usePriceProductQuery()
                    ->filterByFkProduct($priceProductCriteriaTransfer->getIdProductConcrete())
                ->endUse();
        }

        if ($priceProductCriteriaTransfer->getPriceProductStoreIds()) {
            $priceProductStoreQuery->filterByIdPriceProductStore_In($priceProductCriteriaTransfer->getPriceProductStoreIds());
        }

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

        return $this->buildQueryFromCriteria($priceProductDefaultQuery)
            ->findOne();
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
     * @param array<int> $productAbstractIds
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesByIdInAndCriteria(
        array $productAbstractIds,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        if (!$priceProductCriteriaTransfer) {
            $priceProductCriteriaTransfer = new PriceProductCriteriaTransfer();
        }

        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);

        $priceProductStoreQuery
            ->innerJoinWithPriceProduct()
            ->usePriceProductQuery()
                ->joinWithPriceType()
                ->filterByFkProductAbstract_In($productAbstractIds)
            ->endUse();

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            new PriceProductCriteriaTransfer(),
            $priceProductStoreQuery,
        );

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    public function findIdPriceProductStoreByPriceProduct(PriceProductTransfer $priceProductTransfer): ?int
    {
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
        $currencyTransfer = $moneyValueTransfer->requireCurrency()->getCurrency();

        $priceProductStoreEntity = $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
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
        $priceProductTransfer->requireIdPriceProduct();

        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
        $currencyTransfer = $moneyValueTransfer->requireCurrency()->getCurrency();

        $priceProductStoreEntityQuery = $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency(), Criteria::NOT_EQUAL)
            ->_or()
            ->filterByFkStore($moneyValueTransfer->getFkStore(), Criteria::NOT_EQUAL);

        return $this->buildQueryFromCriteria($priceProductStoreEntityQuery)->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return array<\Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer>
     */
    public function findPriceProductStoresByPriceProduct(PriceProductTransfer $priceProductTransfer): array
    {
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer */
        $currencyTransfer = $moneyValueTransfer->requireCurrency()->getCurrency();

        $priceProductStoreEntityQuery = $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->filterByFkStore($moneyValueTransfer->getFkStore());

        return $this->buildQueryFromCriteria($priceProductStoreEntityQuery)->find();
    }

    /**
     * @param array<string> $concreteSkus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductAbstractPricesByConcreteSkusAndCriteria(
        array $concreteSkus,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);

        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $priceProductStoreQuery
            ->joinWithCurrency()
            ->addAsColumn('product_sku', 'spy_product.sku')
            ->innerJoinWithPriceProduct()
            ->usePriceProductQuery()
                ->innerJoinWithSpyProductAbstract()
                ->useSpyProductAbstractQuery()
                    ->useSpyProductQuery()
                        ->filterBySku_In($concreteSkus)
                    ->endUse()
                ->endUse()
            ->endUse();

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            $priceProductCriteriaTransfer,
            $priceProductStoreQuery,
        );

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities, $concreteSkus);
    }

    /**
     * @param array<string> $concreteSkus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductConcretePricesByConcreteSkusAndCriteria(
        array $concreteSkus,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array {
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer);

        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $priceProductStoreQuery
            ->joinWithCurrency()
            ->joinWithPriceProduct()
            ->usePriceProductQuery()
                ->innerJoinWithProduct()
                ->useProductQuery()
                    ->filterBySku_In($concreteSkus)
                ->endUse()
            ->endUse();

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            $priceProductCriteriaTransfer,
            $priceProductStoreQuery,
        );

        return $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function isPriceProductByProductIdentifierAndPriceTypeExists(PriceProductTransfer $priceProductTransfer): bool
    {
        $priceProductQuery = $this->getFactory()
            ->createPriceProductQuery()
            ->filterByFkPriceType($priceProductTransfer->getFkPriceType());

        $priceProductQuery = $this->addProductIdentifierToQuery(
            $priceProductTransfer,
            $priceProductQuery,
        );

        return $priceProductQuery->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery $priceProductQuery
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    protected function addProductIdentifierToQuery(PriceProductTransfer $priceProductTransfer, SpyPriceProductQuery $priceProductQuery): SpyPriceProductQuery
    {
        $idProduct = $priceProductTransfer->getIdProduct();

        if ($idProduct !== null) {
            return $priceProductQuery->filterByFkProduct($idProduct);
        }

        return $priceProductQuery->filterByFkProductAbstract($priceProductTransfer->getIdProductAbstract());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductPricesByCriteria(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ArrayObject
    {
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = $this->createBasePriceProductStoreQuery($priceProductCriteriaTransfer)
            ->joinWithCurrency()
            ->addAsColumn('product_sku', SpyProductTableMap::COL_SKU)
            ->innerJoinWithPriceProduct()
            ->usePriceProductQuery()
                ->joinWithPriceType()
                ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithSpyProduct()
                ->endUse()
            ->endUse();

        $priceProductStoreEntities = $this->getPriceProductStoreEntitiesPerDimension(
            $priceProductCriteriaTransfer,
            $priceProductStoreQuery,
        );

        $priceProductStoreTransfers = $this->getFactory()
            ->createPriceProductMapper()
            ->mapPriceProductStoreEntitiesToPriceProductTransfers($priceProductStoreEntities);

        return new ArrayObject($priceProductStoreTransfers);
    }
}
