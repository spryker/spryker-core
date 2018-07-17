<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Persistence;

use Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductCategoryFilterStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $idCategories
     *
     * @return $this|\Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery
     */
    public function queryProductCategoryFilterStorageByFkCategories(array $idCategories);

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return $this|\Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryByIdCategories(array $categoryIds);

    /**
     * @api
     *
     * @param int[] $productCategoryFilterIds
     *
     * @return $this|\Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryByCategoryFilterIds(array $productCategoryFilterIds): SpyProductCategoryFilterQuery;
}
