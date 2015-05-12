<?php

namespace SprykerFeature\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;
use Generated\Shared\Transfer\CategoryCategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Category\Transfer\CategoryNode;

/**
 * @method CategoryDependencyContainer getDependencyContainer()
 */
class CategoryFacade extends AbstractFacade
{

    /**
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->hasCategoryNode($categoryName, $locale)
        ;
    }

    /**
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getCategoryNodeIdentifier($categoryName, $locale)
        ;
    }

    /**
     * @param CategoryCategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function createCategory(CategoryCategoryTransfer $category, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryWriter()
            ->create($category, $locale)
        ;
    }

    /**
     * @param CategoryCategoryTransfer $category
     * @param LocaleTransfer $locale
     */
    public function updateCategory(CategoryCategoryTransfer $category, LocaleTransfer $locale)
    {
        $this->getDependencyContainer()
            ->createCategoryWriter()
            ->update($category, $locale)
        ;
    }

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function deleteCategoryByNodeId($idNode, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->deleteCategoryByNodeId($idNode, $locale)
        ;
    }

    /**
     * @param CategoryCategoryNodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     *
     * @return int $nodeId
     */
    public function createCategoryNode(CategoryCategoryNodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->createCategoryNode($categoryNode, $locale)
        ;
    }

    /**
     * @param CategoryCategoryNodeTransfer $categoryNode
     * @throws \ErrorException
     */
    public function moveCategoryNode(CategoryCategoryNodeTransfer $categoryNode)
    {
        $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->moveNode($categoryNode)
        ;
    }

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $locale, $deleteChildren = false)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->deleteNode($idNode, $locale, $deleteChildren)
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
     * @param LocaleTransfer $locale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getChildren($idNode, LocaleTransfer $locale, $onlyOneLevel = true, $excludeStartNode = true)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getChildren($idNode, $locale, $onlyOneLevel, $excludeStartNode)
        ;
    }

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $locale, $excludeStartNode = true)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getParents($idNode, $locale, $excludeStartNode)
        ;
    }
}
