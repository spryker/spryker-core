<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer;
use Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductListStorage\Persistence\ProductListStoragePersistenceFactory getFactory()
 */
class ProductListStorageRepository extends AbstractRepository implements ProductListStorageRepositoryInterface
{
    /**
     * @module Product
     *
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = $this->getFactory()
            ->getProductPropelQuery()
            ->select(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT);

        return $productQuery
            ->filterByIdProduct_In($productConcreteIds)
            ->find()
            ->toArray();
    }

    /**
     * @module Product
     *
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function findProductConcreteIdsByProductAbstractIds(array $productAbstractIds): array
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = $this->getFactory()
            ->getProductPropelQuery()
            ->select(SpyProductTableMap::COL_ID_PRODUCT);

        return $productQuery
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->distinct()
            ->find()
            ->toArray();
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[]
     */
    public function findProductAbstractProductListStorageEntities(array $productAbstractIds): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $productAbstractProductListStorageQuery = $this->getFactory()
            ->createProductAbstractProductListStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);

        $productAbstractProductListStorageCollection = $productAbstractProductListStorageQuery->find();

        $result = [];
        foreach ($productAbstractProductListStorageCollection as $spyProductAbstractProductListStorage) {
            $result[] = $spyProductAbstractProductListStorage;
        }

        return $result;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[]
     */
    public function findProductConcreteProductListStorageEntities(array $productConcreteIds): array
    {
        if (!$productConcreteIds) {
            return [];
        }

        $productConcreteProductListStorageQuery = $this->getFactory()
            ->createProductConcreteProductListStorageQuery()
            ->filterByFkProduct_In($productConcreteIds);

        $productConcreteProductListStorageCollection = $productConcreteProductListStorageQuery->find();

        $result = [];
        foreach ($productConcreteProductListStorageCollection as $spyProductConcreteProductListStorage) {
            $result[] = $spyProductConcreteProductListStorage;
        }

        return $result;
    }

    /**
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage[]
     */
    public function findAllProductAbstractProductListStorageEntities(): array
    {
        $productAbstractProductListStorageCollection = $this->getFactory()
            ->createProductAbstractProductListStorageQuery()
            ->find();

        return $productAbstractProductListStorageCollection->getArrayCopy();
    }

    /**
     * @return \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage[]
     */
    public function findAllProductConcreteProductListStorageEntities(): array
    {
        $productConcreteProductListStorageCollection = $this->getFactory()
            ->createProductConcreteProductListStorageQuery()
            ->find();

        return $productConcreteProductListStorageCollection->getArrayCopy();
    }

    /**
     * @module ProductList
     *
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

    /**
     * @module ProductCategory
     *
     * @param array $categoryIds
     *
     * @return array
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        /** @var \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery $productCategoryQuery */
        $productCategoryQuery = $this->getFactory()
            ->getProductCategoryPropelQuery()
            ->select(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT);

        return $productCategoryQuery
            ->filterByFkCategory_In($categoryIds)
            ->distinct()
            ->find()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer[]
     */
    public function findFilteredProductConcreteProductListStorageEntities(FilterTransfer $filterTransfer, array $productConcreteIds = []): array
    {
        $productConcreteProductListStorageEntityTransfers = [];
        $mapper = $this->getFactory()->createProductListStorageMapper();
        $query = $this->getFactory()->createProductConcreteProductListStorageQuery();

        if ($productConcreteIds) {
            $query->filterByFkProduct_In($productConcreteIds);
        }

        $productConcreteProductListStorageEntities = $query->offset($filterTransfer->getOffset())
            ->limit($filterTransfer->getLimit())
            ->find();

        foreach ($productConcreteProductListStorageEntities as $productConcreteProductListStorageEntity) {
            $productConcreteProductListStorageEntityTransfers[] = $mapper->mapProductConcreteProductListStorageEntitiesToTransfers(
                $productConcreteProductListStorageEntity,
                new SpyProductConcreteProductListStorageEntityTransfer()
            );
        }

        return $productConcreteProductListStorageEntityTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer[]
     */
    public function findFilteredProductAbstractProductListStorageEntities(FilterTransfer $filterTransfer, array $productAbstractIds = []): array
    {
        $productAbstractProductListStorageEntityTransfers = [];
        $mapper = $this->getFactory()->createProductListStorageMapper();
        $query = $this->getFactory()->createProductAbstractProductListStorageQuery();

        if ($productAbstractIds) {
            $query->filterByFkProductAbstract_In($productAbstractIds);
        }

        $productAbstractProductListStorageEntities = $query->offset($filterTransfer->getOffset())
            ->limit($filterTransfer->getLimit())
            ->find();

        foreach ($productAbstractProductListStorageEntities as $productAbstractProductListStorageEntity) {
            $productAbstractProductListStorageEntityTransfers[] = $mapper->mapProductAbstractProductListStorageEntitiesToTransfers(
                $productAbstractProductListStorageEntity,
                new SpyProductAbstractProductListStorageEntityTransfer()
            );
        }

        return $productAbstractProductListStorageEntityTransfers;
    }
}
