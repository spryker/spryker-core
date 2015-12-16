<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CategoryDependencyContainer getBusinessFactory()
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
        return $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->hasCategoryNode($categoryName, $locale);
    }

    /**
     * @param int $idNode
     *
     * @return NodeTransfer
     */
    public function getNodeById($idNode)
    {
        $nodeEntity = $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getNodeById($idNode);

        return $this->getBusinessFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNode($nodeEntity);
    }

    /**
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $locale)
    {
        return $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getCategoryNodeIdentifier($categoryName, $locale);
    }

    /**
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $locale)
    {
        return $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getCategoryIdentifier($categoryName, $locale);
    }

    /**
     * @param int $idCategory
     *
     * @return NodeTransfer[]
     */
    public function getAllNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getAllNodesByIdCategory($idCategory);

        return $this->getBusinessFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @param int $idCategory
     *
     * @return NodeTransfer[]
     */
    public function getMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getMainNodesByIdCategory($idCategory);

        return $this->getBusinessFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @param int $idCategory
     *
     * @return NodeTransfer[]
     */
    public function getNotMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getNotMainNodesByIdCategory($idCategory);

        return $this->getBusinessFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function createCategory(CategoryTransfer $category, LocaleTransfer $locale)
    {
        return $this->getBusinessFactory()
            ->createCategoryWriter()
            ->create($category, $locale);
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $this->getBusinessFactory()
            ->createCategoryWriter()
            ->update($category, $locale);
    }

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    public function addCategoryAttribute(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $this->getBusinessFactory()
            ->createCategoryWriter()
            ->addCategoryAttribute($category, $locale);
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory($idCategory)
    {
        $this->getBusinessFactory()
            ->createCategoryWriter()
            ->delete($idCategory);
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
        return $this->getBusinessFactory()
            ->createCategoryTreeWriter()
            ->createCategoryNode($categoryNode, $locale, $createUrlPath);
    }

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    public function updateCategoryNode(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $this->getBusinessFactory()
            ->createCategoryTreeWriter()
            ->updateNode($categoryNode, $locale);
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
        return $this->getBusinessFactory()
            ->createCategoryTreeWriter()
            ->deleteNode($idNode, $locale, $deleteChildren);
    }

    /**
     * @return bool
     */
    public function renderCategoryTreeVisual()
    {
        return $this->getBusinessFactory()
            ->createCategoryTreeRenderer()
            ->render();
    }

    /**
     * @return NodeTransfer[]
     */
    public function getRootNodes()
    {
        $rootNodes = $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getRootNodes();

        return $this->getBusinessFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($rootNodes);
    }

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function getTree($idCategory, LocaleTransfer $locale)
    {
        return $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getTree($idCategory, $locale);
    }

    /**
     * @param int $idNode
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function getChildren($idNode, LocaleTransfer $locale)
    {
        return $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getChildren($idNode, $locale);
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
        return $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getParents($idNode, $locale, $excludeStartNode);
    }

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $locale)
    {
        return $this->getBusinessFactory()
            ->createCategoryTreeReader()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idCategory, $locale);
    }

    /**
     * @return void
     */
    public function rebuildClosureTable()
    {
        $this->getBusinessFactory()
            ->createCategoryTreeWriter()
            ->rebuildClosureTable();
    }

    /**
     * @param array $pathTokens
     *
     * @return string
     */
    public function generatePath(array $pathTokens)
    {
        return $this->getBusinessFactory()
            ->createUrlPathGenerator()
            ->generate($pathTokens);
    }

}
