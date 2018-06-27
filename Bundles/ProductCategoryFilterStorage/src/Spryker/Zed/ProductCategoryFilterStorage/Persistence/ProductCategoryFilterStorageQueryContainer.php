<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStoragePersistenceFactory getFactory()
 */
class ProductCategoryFilterStorageQueryContainer extends AbstractQueryContainer implements ProductCategoryFilterStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $idCategories
     *
     * @return $this|\Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery
     */
    public function queryProductCategoryFilterStorageByFkCategories(array $idCategories)
    {
        return $this->getFactory()
            ->createSpyProductCategoryFilterStorageQuery()
            ->filterByFkCategory_In($idCategories);
    }

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return $this|\Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryByIdCategories(array $categoryIds)
    {
        return $this->getFactory()
            ->getProductCategoryFilterQuery()
            ->queryProductCategoryFilter()
            ->filterByFkCategory_In($categoryIds);
    }

    /**
     * @api
     *
     * @return $this|\Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryFilter()
    {
        return $this->getFactory()
            ->getProductCategoryFilterQuery()
            ->queryProductCategoryFilter();
    }
}
