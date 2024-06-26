<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductListStorage\Persistence\ProductListStoragePersistenceFactory getFactory()
 */
class ProductListStorageRepository extends AbstractRepository implements ProductListStorageRepositoryInterface
{
    /**
     * @module Product
     *
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = $this->getFactory()
            ->getProductPropelQuery()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductTableMap::COL_ID_PRODUCT]);

        /** @var \Propel\Runtime\Collection\ObjectCollection $productAbstractIds */
        $productAbstractIds = $productQuery
            ->filterByIdProduct_In($productConcreteIds)
            ->find();

        return $productAbstractIds->toKeyValue(SpyProductTableMap::COL_ID_PRODUCT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT);
    }

    /**
     * @module Product
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByProductAbstractIds(array $productAbstractIds): array
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = $this->getFactory()
            ->getProductPropelQuery()
            ->select(SpyProductTableMap::COL_ID_PRODUCT);

        /** @var \Propel\Runtime\Collection\ArrayCollection $productConcreteIds */
        $productConcreteIds = $productQuery
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->distinct()
            ->find();

        return $productConcreteIds->toArray();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage>
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
     * @param array<int> $productConcreteIds
     *
     * @return array<\Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage>
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
     * @return array<\Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage>
     */
    public function findAllProductAbstractProductListStorageEntities(): array
    {
        $productAbstractProductListStorageCollection = $this->getFactory()
            ->createProductAbstractProductListStorageQuery()
            ->find();

        return $productAbstractProductListStorageCollection->getArrayCopy();
    }

    /**
     * @return array<\Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage>
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
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByProductListIds(array $productListIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productConcreteIds */
        $productConcreteIds = $this->getFactory()
            ->getProductListProductConcretePropelQuery()
            ->filterByFkProductList_In($productListIds)
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT)
            ->distinct()
            ->find();

        return $productConcreteIds->toArray();
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

        /** @var \Propel\Runtime\Collection\ArrayCollection $productAbstractIds */
        $productAbstractIds = $productCategoryQuery
            ->filterByFkCategory_In($categoryIds)
            ->distinct()
            ->find();

        return $productAbstractIds->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer>
     */
    public function findFilteredProductConcreteProductListStorageEntities(FilterTransfer $filterTransfer, array $productConcreteIds = []): array
    {
        $query = $this->getFactory()->createProductConcreteProductListStorageQuery();

        if ($productConcreteIds) {
            $query->filterByFkProduct_In($productConcreteIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer>
     */
    public function findFilteredProductAbstractProductListStorageEntities(FilterTransfer $filterTransfer, array $productAbstractIds = []): array
    {
        $query = $this->getFactory()->createProductAbstractProductListStorageQuery();

        if ($productAbstractIds) {
            $query->filterByFkProductAbstract_In($productAbstractIds);
        }

        return $this->buildQueryFromCriteria($query, $filterTransfer)->find();
    }

    /**
     * @return int
     */
    public function getProductListWhitelistEnumValue(): int
    {
        return $this->getProductListTypeEnumValueByTypeName(SpyProductListTableMap::COL_TYPE_WHITELIST);
    }

    /**
     * @return int
     */
    public function getProductListBlacklistEnumValue(): int
    {
        return $this->getProductListTypeEnumValueByTypeName(SpyProductListTableMap::COL_TYPE_BLACKLIST);
    }

    /**
     * @param string $type
     *
     * @return int
     */
    protected function getProductListTypeEnumValueByTypeName(string $type): int
    {
        $enumForType = array_flip(SpyProductListTableMap::getValueSet(SpyProductListTableMap::COL_TYPE));

        return $enumForType[$type];
    }
}
