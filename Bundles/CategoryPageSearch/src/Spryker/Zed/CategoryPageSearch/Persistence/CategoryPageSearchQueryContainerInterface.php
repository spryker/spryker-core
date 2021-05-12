<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CategoryPageSearchQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - Creates category node query.
     * - Finds all category node entities sorted by node order.
     * - Filters query on the `fk_category` column.
     *
     * @api
     *
     * @deprecated Will be removed with next major release.
     *
     * @param int[] $categoryIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryNodeIdsByCategoryIds(array $categoryIds);

    /**
     * Specification:
     * - Creates category node page query.
     * - Filters query on the `fk_category_node` column.
     *
     * @api
     *
     * @param int[] $categoryNodeIds
     *
     * @return \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery
     */
    public function queryCategoryNodePageSearchByIds(array $categoryNodeIds);

    /**
     * Specification:
     * - Creates category node query.
     * - Finds all category node entities sorted by node order.
     * - Filters query on the `fk_category_template` column.
     *
     * @api
     *
     * @param int[] $categoryTemplateIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryNodeIdsByTemplateIds(array $categoryTemplateIds);

    /**
     * Specification:
     * - Creates category node query.
     * - Finds all category node entities sorted by node order.
     * - Filters query on the `id_category_node` column.
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodesByIds(array $ids): SpyCategoryNodeQuery;
}
