<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CategoryPageSearchQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $localeNames
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocalesWithLocaleNames(array $localeNames);

    /**
     * @api
     *
     * @param array $categoryNodeIds
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeTree(array $categoryNodeIds, $idLocale);

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryRoot();

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
     * @return \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery
     */
    public function queryCategoryNodePageSearchByIds(array $categoryNodeIds);

    /**
     * @api
     *
     * @param array $categoryTemplateIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeIdsByTemplateIds(array $categoryTemplateIds);

    /**
     * @api
     *
     * @param int[] $ids
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodesByIds(array $ids): SpyCategoryNodeQuery;
}
