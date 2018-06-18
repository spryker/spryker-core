<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Persistence;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListCategoryTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductList\Persistence\ProductListPersistenceFactory getFactory()
 */
class ProductListRepository extends AbstractRepository implements ProductListRepositoryInterface
{
    /**
     * @param int $idProductList
     *
     * @return int[]
     */
    public function getRelatedCategoryIdsByIdProductList(int $idProductList): array
    {
        return $this->getFactory()
            ->createProductListCategoryQuery()
            ->select(SpyProductListCategoryTableMap::COL_FK_CATEGORY)
            ->findByFkProductList($idProductList)
            ->toArray();
    }

    /**
     * @param int $idProductList
     *
     * @return int[]
     */
    public function getRelatedProductConcreteIdsByIdProductList(int $idProductList): array
    {
        return $this->getFactory()
            ->createProductListProductConcreteQuery()
            ->select(SpyProductListProductConcreteTableMap::COL_FK_PRODUCT)
            ->findByFkProductList($idProductList)
            ->toArray();
    }
}
