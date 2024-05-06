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
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductPageSearch\Persistence\Map\SpyProductConcretePageSearchTableMap;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchPersistenceFactory getFactory()
 */
class ProductPageSearchRepository extends AbstractRepository implements ProductPageSearchRepositoryInterface
{
    /**
     * @var string
     */
    protected const FK_PRODUCT_ABSTRACT = 'fkProductAbstract';

    /**
     * @var string
     */
    protected const FK_CATEGORY = 'fkCategory';

    /**
     * @uses \Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_ABSTRACT = 'spy_product_category.fk_product_abstract';

    /**
     * @uses \Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap::COL_FK_CATEGORY
     *
     * @var string
     */
    protected const COL_FK_CATEGORY = 'spy_category_node.fk_category';

    /**
     * @param array<int> $productSearchIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByProductSearchIds(array $productSearchIds): array
    {
        if (!$productSearchIds) {
            return [];
        }

        return $this->getFactory()
            ->getProductSearchQuery()
            ->filterByIdProductSearch_In($productSearchIds)
            ->select(SpyProductSearchTableMap::COL_FK_PRODUCT)
            ->distinct()
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function getProductConcretePageSearchTransfers(array $productIds): array
    {
        $productConcretePageSearchEntities = $this->getFactory()
            ->createProductConcretePageSearchQuery()
            ->filterByFkProduct_In($productIds)
            ->find();

        return $this->mapProductConcretePageSearchEntities(
            $productConcretePageSearchEntities,
        );
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<int>
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
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function getProductConcretePageSearchTransfersByProductAbstractStoreMap(array $productAbstractStoreMap): array
    {
        $productConcretePageSearchEntities = $this->getProductConcretePageSearchEntitiesByAbstractProductsAndStores($productAbstractStoreMap);

        return $this->mapProductConcretePageSearchEntities(
            $productConcretePageSearchEntities,
        );
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int>
     */
    public function getEligibleForAddToCartProductAbstractsIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        /** @var \Propel\Runtime\Collection\ArrayCollection $productAbstractIds */
        $productAbstractIds = $this->getFactory()
            ->getProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->filterByIsActive(true)
            ->groupByFkProductAbstract()
            ->having(sprintf('COUNT(%s) = ?', SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT), 1)
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find();

        return $productAbstractIds->toArray();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<string>
     */
    public function getProductConcreteSkusByProductAbstractIds(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $productConcreteSkus */
        $productConcreteSkus = $this->getFactory()
            ->getProductQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->select([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductTableMap::COL_SKU,
            ])
            ->find();

        return $productConcreteSkus->toKeyValue(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_SKU);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
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
     * @module ProductImage
     *
     * @param list<int> $productImageSetIds
     *
     * @return list<int>
     */
    public function getProductAbstractIdsByProductImageSetIds(array $productImageSetIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productAbstractIds */
        $productAbstractIds = $this->getFactory()
            ->getProductImageSetQuery()
            ->filterByIdProductImageSet_In($productImageSetIds)
            ->select([SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find();

        return $productAbstractIds->toArray();
    }

    /**
     * @module Category
     *
     * @param list<int> $categoryNodeIds
     *
     * @return list<int>
     */
    public function getCategoryIdsByCategoryNodeIds(array $categoryNodeIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $categoryIds */
        $categoryIds = $this->getFactory()
            ->getCategoryClosureTableQuery()
            ->filterByFkCategoryNode_In($categoryNodeIds)
            ->_or()
            ->filterByFkCategoryNodeDescendant_In($categoryNodeIds)
            ->joinDescendantNode()
            ->withColumn(static::COL_FK_CATEGORY, static::FK_CATEGORY)
            ->select([static::FK_CATEGORY])
            ->find();

        return $categoryIds->toArray();
    }

    /**
     * @module ProductCategory
     *
     * @param list<int> $categoryIds
     *
     * @return list<int>
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productAbstractIds */
        $productAbstractIds = $this->getFactory()
            ->getProductCategoryQuery()
            ->filterByFkCategory_In($categoryIds)
            ->select(static::COL_FK_PRODUCT_ABSTRACT)
            ->find();

        return $productAbstractIds->toArray();
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch> $productConcretePageSearchEntities
     *
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    protected function mapProductConcretePageSearchEntities($productConcretePageSearchEntities): array
    {
        $mapper = $this->getFactory()->createProductPageSearchMapper();
        $productConcretePageSearchTransfers = [];
        foreach ($productConcretePageSearchEntities as $productConcretePageSearchEntity) {
            $productConcretePageSearchTransfers[] = $mapper->mapProductConcretePageSearchEntityToTransfer(
                $productConcretePageSearchEntity,
                new ProductConcretePageSearchTransfer(),
            );
        }

        return $productConcretePageSearchTransfers;
    }

    /**
     * @module Product
     *
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch>
     */
    protected function getProductConcretePageSearchEntitiesByAbstractProductsAndStores(array $productAbstractStoreMap)
    {
        /** @var \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery $productConcretePageSearchQuery */
        $productConcretePageSearchQuery = $this->getFactory()
            ->createProductConcretePageSearchQuery()
            ->addJoin(
                SpyProductConcretePageSearchTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_ID_PRODUCT,
                Criteria::INNER_JOIN,
            );

        $storesAndProductsConditions = $this->buildStoresAndProductsConditions(
            $productConcretePageSearchQuery,
            $productAbstractStoreMap,
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
                    PDO::PARAM_INT,
                );
                $conditionIndex++;
                $productConcretePageSearchQuery->condition(
                    (string)$conditionIndex,
                    SpyProductConcretePageSearchTableMap::COL_STORE . ' = ?',
                    $store,
                    PDO::PARAM_STR,
                );
                $conditionIndex++;
                $productConcretePageSearchQuery->combine(
                    [(string)($conditionIndex - 2), (string)($conditionIndex - 1)],
                    Criteria::LOGICAL_AND,
                    (string)$conditionIndex,
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
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductEntityTransfer>
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
     * @param array<int> $priceProductStoreIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByPriceProductStoreIds(array $priceProductStoreIds): array
    {
        if (!$priceProductStoreIds) {
            return [];
        }

        /** @var \Propel\Runtime\Collection\ArrayCollection $productAbstractIds */
        $productAbstractIds = $this->getFactory()
            ->getPriceProductPropelQuery()
            ->select(SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->usePriceProductStoreQuery()
                ->filterByIdPriceProductStore_In($priceProductStoreIds)
            ->endUse()
            ->find();

        return $productAbstractIds->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
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
