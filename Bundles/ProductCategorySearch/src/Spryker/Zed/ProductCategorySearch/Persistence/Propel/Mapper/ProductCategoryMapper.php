<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Persistence\Propel\Mapper;

use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Propel\Runtime\Collection\ObjectCollection;

class ProductCategoryMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory> $productCategoryEntities
     * @param array<array<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory>> $mappedProductCategoryEntities
     *
     * @return array<array<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory>>
     */
    public function mapProductCategoryEntitiesByIdProductAbstractAndStore(
        ObjectCollection $productCategoryEntities,
        array $mappedProductCategoryEntities
    ): array {
        foreach ($productCategoryEntities as $productCategoryEntity) {
            $mappedProductCategoryEntities = $this->mapProductCategoryEntityByIdProductAbstractAndStore(
                $productCategoryEntity,
                $mappedProductCategoryEntities,
            );
        }

        return $mappedProductCategoryEntities;
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategoryEntity
     * @param array<array<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory>> $productCategoryEntities
     *
     * @return array<array<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory>>
     */
    protected function mapProductCategoryEntityByIdProductAbstractAndStore(
        SpyProductCategory $productCategoryEntity,
        array $productCategoryEntities
    ): array {
        foreach ($productCategoryEntity->getSpyCategory()->getSpyCategoryStores() as $categoryStoreEntity) {
            $idProductAbstract = $productCategoryEntity->getFkProductAbstract();
            $storeName = $categoryStoreEntity->getSpyStore()->getName();

            $productCategoryEntities[$idProductAbstract][$storeName][] = $productCategoryEntity;
        }

        return $productCategoryEntities;
    }
}
