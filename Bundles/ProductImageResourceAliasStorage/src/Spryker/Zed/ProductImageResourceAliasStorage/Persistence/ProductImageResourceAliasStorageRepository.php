<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStoragePersistenceFactory getFactory()
 */
class ProductImageResourceAliasStorageRepository extends AbstractRepository implements ProductImageResourceAliasStorageRepositoryInterface
{
    protected const KEY_SKU = '"sku"';

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage[]
     */
    public function getProductAbstractImageStorageEntities(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductAbstractImageStoragePropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $productImageSetIds
     *
     * @return array
     */
    public function getProductAbstractImageSetsSkuList(array $productImageSetIds): array
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $findCriteria */
        $findCriteria = $this->getFactory()
            ->getProductImageSetPropelQuery()
            ->filterByIdProductImageSet_In($productImageSetIds)
            ->joinWithSpyProductAbstract()
            ->select([
                SpyProductImageSetTableMap::COL_ID_PRODUCT_IMAGE_SET,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            ])
            ->addAsColumn(static::KEY_SKU, SpyProductAbstractTableMap::COL_SKU);

        return $findCriteria->find()
            ->toArray(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage[]
     */
    public function getProductConcreteImageStorageEntities(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->getProductConcreteImageStoragePropelQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductConcreteImageSetsSkuList(array $productConcreteIds): array
    {
        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery $findCriteria */
        $findCriteria = $this->getFactory()
            ->getProductImageSetPropelQuery()
            ->filterByFkProduct_In($productConcreteIds)
            ->joinWithSpyProduct()
            ->select([
                SpyProductImageSetTableMap::COL_ID_PRODUCT_IMAGE_SET,
                SpyProductTableMap::COL_ID_PRODUCT,
            ])
            ->addAsColumn(static::KEY_SKU, SpyProductTableMap::COL_SKU);

        return $findCriteria->find()
            ->toArray(SpyProductTableMap::COL_ID_PRODUCT);
    }
}
