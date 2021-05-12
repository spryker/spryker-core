<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductCategoryStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - Returns a query for the table `spy_product_category` filtered by primary ids.
     *
     * @api
     *
     * @param int[] $productCategoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryByProductCategoryIds($productCategoryIds): SpyProductCategoryQuery;

    /**
     * Specification:
     * - Creates product abstract category storage query.
     * - Filters query on the `fk_product_abstract` column.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery
     */
    public function queryProductAbstractCategoryStorageByIds(array $productAbstractIds);

    /**
     * Specification:
     * - Creates product category query.
     * - Filters query on the `fk_category` column.
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductAbstractIdsByCategoryIds(array $categoryIds);

    /**
     * Specification:
     * - Creates category node query.
     * - Finds all category node entities sorted by node order.
     * - Filters query on the `id_category_node` column.
     *
     * @api
     *
     * @param array $nodeIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryIdsByNodeIds(array $nodeIds);
}
