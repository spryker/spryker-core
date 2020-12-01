<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Dependency\QueryContainer;

class CategoryGuiToCategoryQueryContainerBridge implements CategoryGuiToCategoryQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct($categoryQueryContainer)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale)
    {
        return $this->categoryQueryContainer->queryCategory($idLocale);
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @return mixed
     */
    public function queryPath($idNode, $idLocale, $excludeRootNode = true, $onlyParents = false)
    {
        return $this->categoryQueryContainer->queryPath($idNode, $idLocale, $excludeRootNode, $onlyParents);
    }

    /**
     * @return mixed
     */
    public function queryCategoryTemplate()
    {
        return $this->categoryQueryContainer->queryCategoryTemplate();
    }

    /**
     * @param int $idCategory
     *
     * @return mixed
     */
    public function queryCategoryById($idCategory)
    {
        return $this->categoryQueryContainer->queryCategoryById($idCategory);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return mixed
     */
    public function queryUrlByIdCategoryNode($idCategoryNode)
    {
        return $this->categoryQueryContainer->queryUrlByIdCategoryNode($idCategoryNode);
    }

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function getCategoryNodesWithOrder($idParentNode, $idLocale)
    {
        return $this->categoryQueryContainer->getCategoryNodesWithOrder($idParentNode, $idLocale);
    }
}
