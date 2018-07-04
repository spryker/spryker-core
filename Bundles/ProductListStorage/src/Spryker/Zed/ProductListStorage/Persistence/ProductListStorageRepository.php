<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductListStorage\Persistence\ProductListStoragePersistenceFactory getFactory()
 */
class ProductListStorageRepository extends AbstractRepository implements ProductListStorageRepositoryInterface
{
    /**
     * @uses SpyProductQuery
     *
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->getProductQuery()
            ->select(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->filterByIdProduct_In($productConcreteIds)
            ->find()
            ->toArray();
    }

    /**
     * @uses SpyProductQuery
     *
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function findProductConcreteIdsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductQuery()
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->distinct()
            ->find()
            ->toArray();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer[]
     */
    public function findProductAbstractProductListStorageEntities(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $productAbstractProductListStorageQuery = $this->getFactory()
            ->createProductAbstractProductListStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);

        return $this->buildQueryFromCriteria($productAbstractProductListStorageQuery)->find();
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer[]
     */
    public function findProductConcreteProductListStorageEntities(array $productConcreteIds): array
    {
        if (!$productConcreteIds) {
            return [];
        }

        $productConcreteProductListStorageQuery = $this->getFactory()
            ->createProductConcreteProductListStorageQuery()
            ->filterByFkProduct_In($productConcreteIds);

        return $this->buildQueryFromCriteria($productConcreteProductListStorageQuery)->find();
    }

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function findProductConcreteIdsByProductListIds(array $productListIds): array
    {
        return $this->getFactory()
            ->getProductListProductConcretePropelQuery()
            ->filterByFkProductList_In($productListIds)
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT)
            ->distinct()
            ->find()
            ->toArray();
    }
}
