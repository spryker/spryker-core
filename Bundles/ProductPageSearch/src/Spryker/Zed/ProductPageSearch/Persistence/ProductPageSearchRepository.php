<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductPageSearch\Persistence\Map\SpyProductConcretePageSearchTableMap;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchPersistenceFactory getFactory()
 */
class ProductPageSearchRepository extends AbstractRepository implements ProductPageSearchRepositoryInterface
{
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
     * @module Product
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function getProductEntityByFilter(FilterTransfer $filterTransfer): array
    {
        $query = $this->getFactory()
            ->getProductQuery()
            ->limit($filterTransfer->getLimit())
            ->offset($filterTransfer->getOffset());

        return $this->buildQueryFromCriteria($query)->find();
    }
}
