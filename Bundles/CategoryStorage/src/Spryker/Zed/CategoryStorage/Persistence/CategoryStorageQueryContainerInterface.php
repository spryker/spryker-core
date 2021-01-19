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
     * @api
     *
     * @param int[] $categoryNodeIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeByIds(array $categoryNodeIds): SpyCategoryNodeQuery;

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryRoot();

    /**
     * @api
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery
     */
    public function queryCategoryStorage();

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeIdsByCategoryIds(array $categoryIds);

    /**
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery
     */
    public function queryCategoryNodeStorageByIds(array $categoryNodeIds);

    /**
     * @api
     *
     * @param array $categoryTemplateIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeIdsByTemplateIds(array $categoryTemplateIds);
}
