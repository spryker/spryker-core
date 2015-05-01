<?php

namespace SprykerFeature\Zed\Category\Business;

use SprykerEngine\Shared\Dto\LocaleDto;
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
     * @param LocaleDto $locale
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleDto $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->hasCategoryNode($categoryName, $locale)
        ;
    }

    /**
     * @param string $categoryName
     * @param LocaleDto $locale
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleDto $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getCategoryNodeIdentifier($categoryName, $locale)
        ;
    }

    /**
     * @param Category $category
     * @param LocaleDto $locale
     *
     * @return int
     */
    public function createCategory(Category $category, LocaleDto $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryWriter()
            ->create($category, $locale)
        ;
    }

    /**
     * @param Category $category
     * @param LocaleDto $locale
     */
    public function updateCategory(Category $category, LocaleDto $locale)
    {
        $this->getDependencyContainer()
            ->createCategoryWriter()
            ->update($category, $locale)
        ;
    }

    /**
     * @param int $idNode
     * @param LocaleDto $locale
     *
     * @return bool
     */
    public function deleteCategoryByNodeId($idNode, LocaleDto $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->deleteCategoryByNodeId($idNode, $locale)
        ;
    }

    /**
     * @param CategoryNode $categoryNode
     * @param LocaleDto $locale
     *
     * @return int $nodeId
     */
    public function createCategoryNode(CategoryNode $categoryNode, LocaleDto $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->createCategoryNode($categoryNode, $locale)
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
     * @param LocaleDto $locale
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleDto $locale, $deleteChildren = false)
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
     * @param LocaleDto $locale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getChildren($idNode, LocaleDto $locale, $onlyOneLevel = true, $excludeStartNode = true)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getChildren($idNode, $locale, $onlyOneLevel, $excludeStartNode)
        ;
    }

    /**
     * @param int $idNode
     * @param LocaleDto $locale
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleDto $locale, $excludeStartNode = true)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getParents($idNode, $locale, $excludeStartNode)
        ;
    }
}
