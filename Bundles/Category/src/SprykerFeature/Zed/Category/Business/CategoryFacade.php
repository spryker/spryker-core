<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Category\Business;

use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Locale\LocaleInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

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
     * @param int $idNode
     *
     * @return NodeTransfer
     */
    public function getNodeById($idNode)
    {
        $nodeEntity = $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getNodeById($idNode)
        ;

        return $this->getDependencyContainer()
            ->createCategoryTransferGenerator()
            ->convertCategoryNode($nodeEntity)
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
     * @return NodeTransfer[]
     */
    public function getAllNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getAllNodesByIdCategory($idCategory)
        ;

        return $this->getDependencyContainer()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities)
        ;
    }

    /**
     * @param int $idCategory
     *
     * @return NodeTransfer[]
     */
    public function getMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getMainNodesByIdCategory($idCategory)
        ;

        return $this->getDependencyContainer()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities)
        ;
    }

    /**
     * @param int $idCategory
     *
     * @return NodeTransfer[]
     */
    public function getNotMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getNotMainNodesByIdCategory($idCategory)
        ;

        return $this->getDependencyContainer()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities)
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
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $this->getDependencyContainer()
            ->createCategoryWriter()
            ->update($category, $locale)
        ;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory($idCategory)
    {
        $this->getDependencyContainer()
            ->createCategoryWriter()
            ->delete($idCategory)
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
     * @param LocaleTransfer $locale
     *
     * @return void
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
     * @return NodeTransfer[]
     */
    public function getRootNodes()
    {
        $rootNodes = $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getRootNodes()
        ;

        return $this->getDependencyContainer()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($rootNodes)
        ;
    }

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     *
     * @return array
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
        return $this->getDependencyContainer()
            ->createCategoryTreeReader()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idCategory, $locale)
        ;
    }

    /**
     * @return void
     */
    public function rebuildClosureTable()
    {
        $this->getDependencyContainer()
            ->createCategoryTreeWriter()
            ->rebuildClosureTable()
        ;
    }

    /**
     * @param array $pathTokens
     *
     * @return string
     */
    public function generatePath(array $pathTokens)
    {
        return $this->getDependencyContainer()
            ->createUrlPathGenerator()
            ->generate($pathTokens)
        ;
    }

}
