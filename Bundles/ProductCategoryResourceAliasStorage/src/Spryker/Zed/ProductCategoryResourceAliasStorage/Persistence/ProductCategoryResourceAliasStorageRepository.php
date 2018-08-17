<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence\ProductCategoryResourceAliasStoragePersistenceFactory getFactory()
 */
class ProductCategoryResourceAliasStorageRepository extends AbstractRepository implements ProductCategoryResourceAliasStorageRepositoryInterface
{
    protected const KEY_SKU = '"sku"';

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage[]
     */
    public function getProductAbstractCategoryStorageEntities(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->getProductAbstractCategoryStoragePropelQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->getData();
    }

    /**
     * @param int[] $productCategoryIds
     *
     * @return array
     */
    public function getProductAbstractCategorysSkuList(array $productCategoryIds): array
    {
        /** @var \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery $findCriteria */
        $findCriteria = $this->getFactory()
            ->getProductCategoryPropelQuery()
            ->filterByIdProductCategory_In($productCategoryIds)
            ->joinWithSpyProductAbstract()
            ->select([
                SpyProductCategoryTableMap::COL_ID_PRODUCT_CATEGORY,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            ])
            ->addAsColumn(static::KEY_SKU, SpyProductAbstractTableMap::COL_SKU);

        return $findCriteria->find()
            ->toArray(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }
}
