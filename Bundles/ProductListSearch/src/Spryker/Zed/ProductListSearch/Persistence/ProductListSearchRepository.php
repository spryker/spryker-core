<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchPersistenceFactory getFactory()
 */
class ProductListSearchRepository extends AbstractRepository implements ProductListSearchRepositoryInterface
{
    /**
     * @uses SpyProductQuery
     *
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByConcreteIds(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->getProductQuery()
            ->select(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->filterByIdProduct_In($productConcreteIds)
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
    public function findProductAbstractIdsByCategoryIds(array $categoryIds): array
    {
        return $this->getFactory()
            ->getProductCategoryPropelQuery()
            ->select(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->filterByFkCategory_In($categoryIds)
            ->distinct()
            ->find()
            ->toArray();
    }
}
