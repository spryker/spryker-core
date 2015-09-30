<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Category\Business;

use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeFormatter;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Locale\LocaleInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNode;

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
     * @param int $idCategory
     * @param $idParentNode
     * 
     * @return SpyCategoryNode
     */
    public function getNodeByIdCategoryAndParentNode($idCategory, $idParentNode)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getNodeByIdCategoryAndParentNode($idCategory, $idParentNode)
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
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getCategoryIdentifier($categoryName, $locale)
        ;
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNode[]
     */
    public function getAllNodesByIdCategory($idCategory)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getAllNodesByIdCategory($idCategory)
        ;
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNode[]
     */
    public function getMainNodesByIdCategory($idCategory)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getMainNodesByIdCategory($idCategory)
        ;
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNode[]
     */
    public function getNotMainNodesByIdCategory($idCategory)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getNotMainNodesByIdCategory($idCategory)
        ;
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function createCategory(CategoryTransfer $category, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryWriter()
            ->create($category, $locale)
        ;
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     */
    public function updateCategory(CategoryTransfer $category, LocaleTransfer $locale)
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
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(NodeTransfer $categoryNode, LocaleTransfer $locale, $createUrlPath = true)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->createCategoryNode($categoryNode, $locale, $createUrlPath)
        ;
    }

    /**
     * @param NodeTransfer $categoryNode
     *
     * @param LocaleTransfer $locale
     */
    public function updateCategoryNode(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->updateNode($categoryNode, $locale)
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
     * @return SpyCategoryNode
     */
    public function getRootNodes()
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getRootNodes()
        ;
    }

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     *
     * @return SpyCategoryNode[]
     */
    public function getTree($idCategory, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getTree($idCategory, $locale)
        ;
    }

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function getChildren($idNode, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getChildren($idNode, $locale)
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

    /**
     * @param int $idCategory
     * @param LocaleInterface $locale
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleInterface $locale)
    {
        $categories = $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getTreeNodeChildren(
                $idCategory,
                $locale
            )
        ;

        return $this->getDependencyContainer()
            ->createCategoryTreeStructure($categories)
            ->getCategoryTree()
        ;
    }

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function getCategoryNodesWithOrder($idNode, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getCategoryNodesWithOrder($idNode, $locale->getIdLocale())
        ;
    }

    /**
     * @param int $idNode
     * @return SpyCategoryNode
     */
    public function getNodeById($idNode)
    {
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getNodeById($idNode)
        ;
    }

    public function rebuildCloseTable()
    {
        $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->rebuildCloseTable()
        ;
    }

}
