<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStoragePersistenceFactory getFactory()
 */
class ProductResourceAliasStorageRepository extends AbstractRepository implements ProductResourceAliasStorageRepositoryInterface
{
    /**
     * @var string
     */
    protected const KEY_SKU = 'sku';

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage>
     */
    public function getProductAbstractStorageEntities(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductAbstractStoragePropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<array<string>>
     */
    public function getProductAbstractSkuList(array $productAbstractIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productAbstractSkus */
        $productAbstractSkus = $this->getFactory()
            ->getProductAbstractPropelQuery()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductAbstractTableMap::COL_SKU => static::KEY_SKU])
            ->find();

        return $productAbstractSkus->toArray(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<\Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage>
     */
    public function getProductConcreteStorageEntities(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->getProductConcreteStoragePropelQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<array<string>>
     */
    public function getProductConcreteSkuList(array $productConcreteIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productConcreteSkus */
        $productConcreteSkus = $this->getFactory()
            ->getProductPropelQuery()
            ->filterByIdProduct_In($productConcreteIds)
            ->select([SpyProductTableMap::COL_ID_PRODUCT, SpyProductTableMap::COL_SKU => static::KEY_SKU])
            ->find();

        return $productConcreteSkus->toArray(SpyProductTableMap::COL_ID_PRODUCT);
    }
}
