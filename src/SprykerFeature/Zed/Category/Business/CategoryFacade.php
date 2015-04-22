<?php

namespace SprykerFeature\Zed\Category\Business;

use SprykerFeature\Shared\Category\Transfer\Category;
use SprykerFeature\Shared\Category\Transfer\CategoryNode;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CategoryDependencyContainer getDependencyContainer()
 */
class CategoryFacade extends AbstractFacade
{

    /**
     * @param string $categoryName
     * @param int $idLocale
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, $idLocale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->hasCategoryNode($categoryName, $idLocale)
        ;
    }

    /**
     * @param string $categoryName
     * @param int $idLocale
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, $idLocale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getCategoryNodeIdentifier($categoryName, $idLocale)
        ;
    }

    /**
     * @param Category $category
     * @param int $idLocale
     *
     * @return int
     */
    public function createCategory(Category $category, $idLocale)
    {
        return $this->getDependencyContainer()
            ->createCategoryWriter()
            ->create($category, $idLocale)
        ;
    }

    /**
     * @param Category $category
     * @param int $idLocale
     */
    public function updateCategory(Category $category, $idLocale)
    {
        $this->getDependencyContainer()
            ->createCategoryWriter()
            ->update($category, $idLocale)
        ;
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     *
     * @return bool
     */
    public function deleteCategoryByNodeId($idNode, $idLocale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->deleteCategoryByNodeId($idNode, $idLocale)
        ;
    }

    /**
     * @param CategoryNode $categoryNode
     * @param int $idLocale
     *
     * @return int $nodeId
     */
    public function createCategoryNode(CategoryNode $categoryNode, $idLocale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->createCategoryNode($categoryNode, $idLocale)
        ;
    }

    /**
     * @param CategoryNode $categoryNode
     * @throws \ErrorException
     */
    public function moveCategoryNode(CategoryNode $categoryNode)
    {
        $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->moveNode($categoryNode)
        ;
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, $idLocale, $deleteChildren = false)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->deleteNode($idNode, $idLocale, $deleteChildren)
        ;
    }

    /**
     * @return bool
     */
    public function renderCategoryTreeVisual()
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeRenderer()
            ->render()
        ;
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getChildren($idNode, $idLocale, $onlyOneLevel = true, $excludeStartNode = true)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getChildren($idNode, $idLocale, $onlyOneLevel, $excludeStartNode)
        ;
    }

    /**
     * @param int $idNode
     * @param string $idLocale
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getParents($idNode, $idLocale, $excludeStartNode = true)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getParents($idNode, $idLocale, $excludeStartNode)
        ;
    }
}
