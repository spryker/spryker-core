<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CategoryStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - Creates category node query.
     * - Finds all category node entities sorted by node order.
     * - Filters query on the `id_category_node` column.
     *
     * @api
     *
     * @param array<int> $categoryNodeIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeByIds(array $categoryNodeIds): SpyCategoryNodeQuery;

    /**
     * Specification:
     * - Creates category tree storage query.
     *
     * @api
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery
     */
    public function queryCategoryStorage();

    /**
     * Specification:
     * - Creates category node query.
     * - Finds all category node entities sorted by node order.
     * - Filters query on the `fk_category` column.
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeIdsByCategoryIds(array $categoryIds);

    /**
     * Specification:
     * - Creates category node storage query.
     * - Filters query on the `fk_category_node` column.
     *
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery
     */
    public function queryCategoryNodeStorageByIds(array $categoryNodeIds);

    /**
     * Specification:
     * - Creates category node query.
     * - Finds all category node entities sorted by node order.
     * - Filters query on the `fk_category_template` column.
     *
     * @api
     *
     * @param array $categoryTemplateIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeIdsByTemplateIds(array $categoryTemplateIds);
}
