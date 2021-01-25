<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductPageSearch\Persistence\Map\SpyProductConcretePageSearchTableMap;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchPersistenceFactory getFactory()
 */
class ProductPageSearchRepository extends AbstractRepository implements ProductPageSearchRepositoryInterface
{
    protected const FK_PRODUCT_ABSTRACT = 'fkProductAbstract';

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfers(array $productIds): array
    {
        $productConcretePageSearchEntities = $this->getFactory()
            ->createProductConcretePageSearchQuery()
            ->filterByFkProduct_In($productIds)
            ->find();

        return $this->mapProductConcretePageSearchEntities(
            $productConcretePageSearchEntities
        );
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductConcreteSkus(array $productConcreteSkus): array
    {
        if (!$productConcreteSkus) {
            return [];
        }

        return $this->getFactory()
            ->getProductQuery()
            ->filterBySku_In($productConcreteSkus)
            ->withColumn(Criteria::DISTINCT . ' ' . SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, static::FK_PRODUCT_ABSTRACT)
            ->select([static::FK_PRODUCT_ABSTRACT])
            ->find()
            ->getData();
    }

    /**
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfersByProductAbstractStoreMap(array $productAbstractStoreMap): array
    {
        $productConcretePageSearchEntities = $this->getProductConcretePageSearchEntitiesByAbstractProductsAndStores($productAbstractStoreMap);

        return $this->mapProductConcretePageSearchEntities(
            $productConcretePageSearchEntities
        );
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function getEligibleForAddToCartProductAbstractsIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        return $this->getFactory()
            ->getProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->filterByIsActive(true)
            ->groupByFkProductAbstract()
            ->having(sprintf('COUNT(%s) = ?', SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT), 1)
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find()
            ->toArray();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return string[]
     */
    public function getProductConcreteSkusByProductAbstractIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $productConcreteSkuMapByIdProductAbstract = $this->getFactory()
            ->getProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->select([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductTableMap::COL_SKU,
            ])
            ->find()
            ->toKeyValue(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_SKU);

        return $productConcreteSkuMapByIdProductAbstract;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getConcreteProductsByProductAbstractIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $productConcreteEntities = $this->getFactory()
            ->getProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find();

        $productConcreteTransfers = [];

        foreach ($productConcreteEntities as $productConcreteEntity) {
            $productConcreteTransfers[] = (new ProductConcreteTransfer())->fromArray($productConcreteEntity->toArray(), true)
                ->setIdProductConcrete($productConcreteEntity->getIdProduct());
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch[]|\Propel\Runtime\Collection\ObjectCollection $productConcretePageSearchEntities
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    protected function mapProductConcretePageSearchEntities($productConcretePageSearchEntities): array
    {
        $mapper = $this->getFactory()->createProductPageSearchMapper();
        $productConcretePageSearchTransfers = [];
        foreach ($productConcretePageSearchEntities as $productConcretePageSearchEntity) {
            $productConcretePageSearchTransfers[] = $mapper->mapProductConcretePageSearchEntityToTransfer(
                $productConcretePageSearchEntity,
                new ProductConcretePageSearchTransfer()
            );
        }

        return $productConcretePageSearchTransfers;
    }

    /**
     * @module Product
     *
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getProductConcretePageSearchEntitiesByAbstractProductsAndStores(array $productAbstractStoreMap)
    {
        /** @var \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery $productConcretePageSearchQuery */
        $productConcretePageSearchQuery = $this->getFactory()
            ->createProductConcretePageSearchQuery()
            ->addJoin(
                SpyProductConcretePageSearchTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_ID_PRODUCT,
                Criteria::INNER_JOIN
            );

        $storesAndProductsConditions = $this->buildStoresAndProductsConditions(
            $productConcretePageSearchQuery,
            $productAbstractStoreMap
        );

        return $productConcretePageSearchQuery->where($storesAndProductsConditions, Criteria::LOGICAL_OR)
            ->distinct()
            ->find();
    }

    /**
     * @module Product
     *
     * Builds related stores and products conditions in the following relation:
     * "(store1 AND product1) OR (store2 AND product1) OR (store2 AND product2)"
     *
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery $productConcretePageSearchQuery
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return array
     */
    protected function buildStoresAndProductsConditions(
        SpyProductConcretePageSearchQuery $productConcretePageSearchQuery,
        array $productAbstractStoreMap
    ): array {
        $storesAndProductsConditions = [];
        $conditionIndex = 1;
        foreach ($productAbstractStoreMap as $abstractId => $stores) {
            foreach ($stores as $store) {
                $productConcretePageSearchQuery->condition(
                    (string)$conditionIndex,
                    SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT . ' = ?',
                    $abstractId,
                    PDO::PARAM_INT
                );
                $conditionIndex++;
                $productConcretePageSearchQuery->condition(
                    (string)$conditionIndex,
                    SpyProductConcretePageSearchTableMap::COL_STORE . ' = ?',
                    $store,
                    PDO::PARAM_STR
                );
                $conditionIndex++;
                $productConcretePageSearchQuery->combine(
                    [(string)($conditionIndex - 2), (string)($conditionIndex - 1)],
                    Criteria::LOGICAL_AND,
                    (string)$conditionIndex
                );
                $storesAndProductsConditions[] = $conditionIndex;
                $conditionIndex++;
            }
        }

        return $storesAndProductsConditions;
    }

    /**
     * @module Product
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function getProductEntityTransfers(array $productIds): array
    {
        $query = $this->getFactory()->getProductQuery();

        if ($productIds !== []) {
            $query->filterByIdProduct_In($productIds);
        }

        return $this->buildQueryFromCriteria($query)->find();
    }

    /**
     * @module PriceProduct
     *
     * @param int[] $priceProductStoreIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByPriceProductStoreIds(array $priceProductStoreIds): array
    {
        if (!$priceProductStoreIds) {
            return [];
        }

        return $this->getFactory()
            ->getPriceProductPropelQuery()
            ->select(SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->usePriceProductStoreQuery()
                ->filterByIdPriceProductStore_In($priceProductStoreIds)
            ->endUse()
            ->find()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByFilterAndProductIds(FilterTransfer $filterTransfer, array $productIds = []): array
    {
        $query = $this->getFactory()
            ->createProductConcretePageSearchQuery();

        if ($productIds !== []) {
            $query->filterByFkProduct_In($productIds);
        }

        $productConcretePageSearchEntityCollection = $this->buildQueryFromCriteria($query, $filterTransfer)
            ->setFormatter(ObjectFormatter::class)
            ->find();

        return $this->getFactory()
            ->createProductPageSearchMapper()
            ->mapProductConcretePageSearchEntityCollectionToSynchronizationDataTransfers($productConcretePageSearchEntityCollection);
    }
}
