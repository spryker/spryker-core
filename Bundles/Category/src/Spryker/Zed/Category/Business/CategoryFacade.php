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
 * @method \Spryker\Zed\Category\Business\CategoryBusinessFactory getFactory()
 */
class CategoryFacade extends AbstractFacade
{

    /**
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->hasCategoryNode($categoryName, $locale);
    }

    /**
     * @param int $idNode
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function getNodeById($idNode)
    {
        $nodeEntity = $this->getFactory()
            ->createCategoryTreeReader()
            ->getNodeById($idNode);

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNode($nodeEntity);
    }

    /**
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryNodeIdentifier($categoryName, $locale);
    }

    /**
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryIdentifier($categoryName, $locale);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getFactory()
            ->createCategoryTreeReader()
            ->getAllNodesByIdCategory($idCategory);

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getFactory()
            ->createCategoryTreeReader()
            ->getMainNodesByIdCategory($idCategory);

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getNotMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this->getFactory()
            ->createCategoryTreeReader()
            ->getNotMainNodesByIdCategory($idCategory);

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return int
     */
    public function createCategory(CategoryTransfer $category, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createCategoryWriter()
            ->create($category, $locale);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $this->getFactory()
            ->createCategoryWriter()
            ->update($category, $locale);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $category
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function addCategoryAttribute(CategoryTransfer $category, LocaleTransfer $locale)
    {
        $this->getFactory()
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
        $this->getFactory()
            ->createCategoryWriter()
            ->delete($idCategory);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(NodeTransfer $categoryNode, LocaleTransfer $locale, $createUrlPath = true)
    {
        return $this->getFactory()
            ->createCategoryTreeWriter()
            ->createCategoryNode($categoryNode, $locale, $createUrlPath);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function updateCategoryNode(NodeTransfer $categoryNode, LocaleTransfer $locale)
    {
        $this->getFactory()
            ->createCategoryTreeWriter()
            ->updateNode($categoryNode, $locale);
    }

    /**
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $locale, $deleteChildren = false)
    {
        return $this->getFactory()
            ->createCategoryTreeWriter()
            ->deleteNode($idNode, $locale, $deleteChildren);
    }

    /**
     * @return bool
     */
    public function renderCategoryTreeVisual()
    {
        return $this->getFactory()
            ->createCategoryTreeRenderer()
            ->render();
    }

    /**
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getRootNodes()
    {
        $rootNodes = $this->getFactory()
            ->createCategoryTreeReader()
            ->getRootNodes();

        return $this->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($rootNodes);
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function getTree($idCategory, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getTree($idCategory, $locale);
    }

    /**
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function getChildren($idNode, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getChildren($idNode, $locale);
    }

    /**
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $locale, $excludeStartNode = true)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getParents($idNode, $locale, $excludeStartNode);
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idCategory, $locale);
    }

    /**
     * @return void
     */
    public function rebuildClosureTable()
    {
        $this->getFactory()
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
        return $this->getFactory()
            ->createUrlPathGenerator()
            ->generate($pathTokens);
    }

}
