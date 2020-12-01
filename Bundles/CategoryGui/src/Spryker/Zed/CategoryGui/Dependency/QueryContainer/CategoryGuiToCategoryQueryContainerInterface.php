<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Dependency\QueryContainer;

interface CategoryGuiToCategoryQueryContainerInterface
{
    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale);

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryPath($idNode, $idLocale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplate();

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryById($idCategory);

    /**
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdCategoryNode($idCategoryNode);

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function getCategoryNodesWithOrder($idParentNode, $idLocale);
}
