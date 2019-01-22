<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
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

        return $this->mapProductConcretePageSearchEntitiesToProductConcretePageSearchTransfer(
            $productConcretePageSearchEntities
        );
    }

    /**
     * @param array $storesPerAbstractProducts
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfersByAbstractProductsAndStores(array $storesPerAbstractProducts): array
    {
        $productConcretePageSearchEntities = $this->getProductConcretePageSearchEntitiesByAbstractProductsAndStores($storesPerAbstractProducts);

        return $this->mapProductConcretePageSearchEntitiesToProductConcretePageSearchTransfer(
            $productConcretePageSearchEntities
        );
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch[]|\Propel\Runtime\Collection\ObjectCollection $productConcretePageSearchEntities
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    protected function mapProductConcretePageSearchEntitiesToProductConcretePageSearchTransfer($productConcretePageSearchEntities): array
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
     * @param array $storesPerAbstractProducts
     *
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getProductConcretePageSearchEntitiesByAbstractProductsAndStores(array $storesPerAbstractProducts)
    {
        $productConcretePageSearchQuery = $this->getFactory()
            ->createProductConcretePageSearchQuery()
            ->addJoin(
                SpyProductConcretePageSearchTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_ID_PRODUCT,
                Criteria::INNER_JOIN
            )->addJoin(
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                Criteria::INNER_JOIN
            );

        $storesAndProductsConditions = $this->buildStoresAndProductsConditions(
                $productConcretePageSearchQuery,
                $storesPerAbstractProducts
            );

        return $productConcretePageSearchQuery->where($storesAndProductsConditions, Criteria::LOGICAL_OR)
            ->distinct()
            ->find();
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearchQuery $productConcretePageSearchQuery
     * @param array $storesPerAbstractProducts
     *
     * @return array
     */
    protected function buildStoresAndProductsConditions(
        SpyProductConcretePageSearchQuery $productConcretePageSearchQuery,
        array $storesPerAbstractProducts
    ): array {
        $storesAndProductsConditions = [];
        $conditionIndex = 1;
        foreach ($storesPerAbstractProducts as $abstractId => $stores) {
            foreach ($stores as $store) {
                $productConcretePageSearchQuery->condition(
                    $conditionIndex,
                    SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT . ' = ?',
                    $abstractId,
                    PDO::PARAM_INT
                );
                $conditionIndex++;
                $productConcretePageSearchQuery->condition(
                    $conditionIndex,
                    SpyProductConcretePageSearchTableMap::COL_STORE . ' = ?',
                    $store,
                    PDO::PARAM_STR
                );
                $conditionIndex++;
                $productConcretePageSearchQuery->combine(
                    [$conditionIndex - 2, $conditionIndex - 1],
                    Criteria::LOGICAL_AND,
                    $conditionIndex
                );
                $storesAndProductsConditions[] = $conditionIndex;
                $conditionIndex++;
            }
        }

        return $storesAndProductsConditions;
    }
}
