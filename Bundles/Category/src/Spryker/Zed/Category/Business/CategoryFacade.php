<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Category\Business\CategoryBusinessFactory getFactory()
 */
class CategoryFacade extends AbstractFacade implements CategoryFacadeInterface
{

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->hasCategoryNode($categoryName, $localeTransfer);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
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
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryNodeIdentifier($categoryName, $localeTransfer);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryIdentifier($categoryName, $localeTransfer);
    }

    /**
     * @api
     *
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
     * @deprecated Will be removed with next major release
     *
     * @api
     *
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
     * @deprecated Will be removed with next major release
     *
     * @api
     *
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
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory)
    {
        return $this
            ->getFactory()
            ->createCategory()
            ->read($idCategory);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return int
     */
    public function createCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createCategoryWriter()
            ->create($categoryTransfer, $localeTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $this->getFactory()->createCategory()->create($categoryTransfer);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer = null)
    {
        $this->getFactory()
            ->createCategoryWriter()
            ->update($categoryTransfer, $localeTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this->getFactory()->createCategory()->update($categoryTransfer);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function addCategoryAttribute(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
    {
        $this->getFactory()
            ->createCategoryWriter()
            ->addCategoryAttribute($categoryTransfer, $localeTransfer);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
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
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this->getFactory()->createCategory()->delete($idCategory);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(NodeTransfer $nodeTransfer, LocaleTransfer $localeTransfer = null, $createUrlPath = true)
    {
        return $this->getFactory()
            ->createCategoryTreeWriter()
            ->createCategoryNode($nodeTransfer, $localeTransfer, $createUrlPath);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function updateCategoryNode(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer = null)
    {
        $this->getFactory()
            ->createCategoryTreeWriter()
            ->updateNode($categoryNodeTransfer, $localeTransfer);
    }

    /**
     * @api
     *
     * @param int $idCategoryNode
     * @param int $position
     *
     * @return void
     */
    public function updateCategoryNodeOrder($idCategoryNode, $position)
    {
        $this
            ->getFactory()
            ->createNodeWriter()
            ->updateOrder($idCategoryNode, $position);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $localeTransfer, $deleteChildren = false)
    {
        return $this->getFactory()
            ->createCategoryTreeWriter()
            ->deleteNode($idNode, $localeTransfer, $deleteChildren);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @return bool
     */
    public function renderCategoryTreeVisual()
    {
        return $this->getFactory()
            ->createCategoryTreeRenderer()
            ->render();
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
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
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTree($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getTree($idCategory, $localeTransfer);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getChildren($idNode, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getChildren($idNode, $localeTransfer);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $localeTransfer, $excludeStartNode = true)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getParents($idNode, $localeTransfer, $excludeStartNode);
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idCategory, $localeTransfer);
    }

    /**
     * @api
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getSubTreeByIdCategoryNodeAndLocale($idCategoryNode, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getSubTree($idCategoryNode, $localeTransfer);
    }

    /**
     * @api
     *
     * @return void
     */
    public function rebuildClosureTable()
    {
        $this
            ->getFactory()
            ->createClosureTableWriter()
            ->rebuildCategoryNodes();
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
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

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param array $categoryKey
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getCategoryByKey($categoryKey, $idLocale)
    {
        return $this->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryByKey($categoryKey, $idLocale);
    }

}
