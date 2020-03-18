<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Persistence;

use Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStoragePersistenceFactory getFactory()
 */
class ProductCategoryFilterStorageQueryContainer extends AbstractQueryContainer implements ProductCategoryFilterStorageQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $idCategories
     *
     * @return \Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery
     */
    public function queryProductCategoryFilterStorageByFkCategories(array $idCategories)
    {
        return $this->getFactory()
            ->createSpyProductCategoryFilterStorageQuery()
            ->filterByFkCategory_In($idCategories);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productCategoryFilterIds
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryByCategoryFilterIds(array $productCategoryFilterIds): SpyProductCategoryFilterQuery
    {
        return $this->getFactory()
            ->getProductCategoryFilterQuery()
            ->queryProductCategoryFilter()
            ->filterByIdProductCategoryFilter_In($productCategoryFilterIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryByIdCategories(array $categoryIds)
    {
        return $this->getFactory()
            ->getProductCategoryFilterQuery()
            ->queryProductCategoryFilter()
            ->filterByFkCategory_In($categoryIds);
    }
}
