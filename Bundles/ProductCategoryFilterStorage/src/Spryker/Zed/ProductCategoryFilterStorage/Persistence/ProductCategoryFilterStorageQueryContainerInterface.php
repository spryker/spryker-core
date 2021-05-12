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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $idCategories
     *
     * @return \Orm\Zed\ProductCategoryFilterStorage\Persistence\SpyProductCategoryFilterStorageQuery
     */
    public function queryProductCategoryFilterStorageByFkCategories(array $idCategories);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryByIdCategories(array $categoryIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $productCategoryFilterIds
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryByCategoryFilterIds(array $productCategoryFilterIds): SpyProductCategoryFilterQuery;
}
