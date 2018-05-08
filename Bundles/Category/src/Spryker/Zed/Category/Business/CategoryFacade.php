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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->hasCategoryNode($categoryName, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function getNodeById($idNode)
    {
        $nodeEntity = $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getNodeById($idNode);

        return $this
            ->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNode($nodeEntity);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryNodeIdentifier($categoryName, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function getCategoryIdentifier($categoryName, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryIdentifier($categoryName, $localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getAllNodesByIdCategory($idCategory);

        return $this
            ->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getMainNodesByIdCategory($idCategory);

        return $this
            ->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getNotMainNodesByIdCategory($idCategory)
    {
        $nodeEntities = $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getNotMainNodesByIdCategory($idCategory);

        return $this
            ->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($nodeEntities);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return int
     */
    public function createCategory(CategoryTransfer $categoryTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        return $this
            ->getFactory()
            ->createCategoryWriter()
            ->create($categoryTransfer, $localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $this
            ->getFactory()
            ->createCategory()
            ->create($categoryTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        $this
            ->getFactory()
            ->createCategoryWriter()
            ->update($categoryTransfer, $localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
     * @throws \Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this
            ->getFactory()
            ->createCategory()
            ->update($categoryTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function addCategoryAttribute(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer)
    {
        $this
            ->getFactory()
            ->createCategoryWriter()
            ->addCategoryAttribute($categoryTransfer, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory($idCategory)
    {
        $this
            ->getFactory()
            ->createCategoryWriter()
            ->delete($idCategory);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this
            ->getFactory()
            ->createCategory()
            ->delete($idCategory);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param int $idChildrenDestinationNode
     *
     * @return void
     */
    public function deleteNodeById($idCategoryNode, $idChildrenDestinationNode)
    {
        $this->getFactory()
            ->createCategoryNode()
            ->deleteNodeById($idCategoryNode, $idChildrenDestinationNode);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(NodeTransfer $nodeTransfer, ?LocaleTransfer $localeTransfer = null, $createUrlPath = true)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeWriter()
            ->createCategoryNode($nodeTransfer, $localeTransfer, $createUrlPath);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    public function updateCategoryNode(NodeTransfer $categoryNodeTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        $this
            ->getFactory()
            ->createCategoryTreeWriter()
            ->updateNode($categoryNodeTransfer, $localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $localeTransfer, $deleteChildren = false)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeWriter()
            ->deleteNode($idNode, $localeTransfer, $deleteChildren);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @return string|false
     */
    public function renderCategoryTreeVisual()
    {
        return $this
            ->getFactory()
            ->createCategoryTreeRenderer()
            ->render();
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getRootNodes()
    {
        $rootNodes = $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getRootNodes();

        return $this
            ->getFactory()
            ->createCategoryTransferGenerator()
            ->convertCategoryNodeCollection($rootNodes);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTree($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getTree($idCategory, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getChildren($idNode, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getChildren($idNode, $localeTransfer);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param bool $excludeStartNode
     *
     * @return array
     */
    public function getParents($idNode, LocaleTransfer $localeTransfer, $excludeStartNode = true)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getParents($idNode, $localeTransfer, $excludeStartNode);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idCategory, $localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
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
     * {@inheritdoc}
     *
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param array $pathTokens
     *
     * @return string
     */
    public function generatePath(array $pathTokens)
    {
        return $this
            ->getFactory()
            ->createUrlPathGenerator()
            ->generate($pathTokens);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param array $categoryKey
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getCategoryByKey($categoryKey, $idLocale)
    {
        return $this
            ->getFactory()
            ->createCategoryTreeReader()
            ->getCategoryByKey($categoryKey, $idLocale);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function touchCategoryActive($idCategory)
    {
        $this
            ->getFactory()
            ->createCategoryToucher()
            ->touchCategoryActive($idCategory);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function syncCategoryTemplate()
    {
        $this->getFactory()
            ->createCategoryTemplateSync()
            ->syncFromConfig();
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer|null
     */
    public function findCategoryTemplateByName($name)
    {
        return $this->getFactory()
            ->createCategoryTemplateReader()
            ->findCategoryTemplateByName($name);
    }
}
