<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryTemplateTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Collection\ObjectCollection;
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
    public function hasCategoryNode(string $categoryName, LocaleTransfer $localeTransfer): bool
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
    public function getNodeById(int $idNode): NodeTransfer
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
    public function getCategoryNodeIdentifier(string $categoryName, LocaleTransfer $localeTransfer): int
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
    public function getCategoryIdentifier(string $categoryName, LocaleTransfer $localeTransfer): int
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
    public function getAllNodesByIdCategory(int $idCategory): array
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
    public function getMainNodesByIdCategory(int $idCategory): array
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
    public function getNotMainNodesByIdCategory(int $idCategory): array
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
    public function read(int $idCategory): CategoryTransfer
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
    public function createCategory(CategoryTransfer $categoryTransfer, ?LocaleTransfer $localeTransfer = null): int
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
    public function create(CategoryTransfer $categoryTransfer): void
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
    public function updateCategory(CategoryTransfer $categoryTransfer, ?LocaleTransfer $localeTransfer = null): void
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
    public function update(CategoryTransfer $categoryTransfer): void
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
    public function addCategoryAttribute(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer): void
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
    public function deleteCategory(int $idCategory): void
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
    public function delete(int $idCategory): void
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
    public function deleteNodeById(int $idCategoryNode, int $idChildrenDestinationNode): void
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
    public function createCategoryNode(NodeTransfer $nodeTransfer, ?LocaleTransfer $localeTransfer = null, bool $createUrlPath = true): int
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
    public function updateCategoryNode(NodeTransfer $categoryNodeTransfer, ?LocaleTransfer $localeTransfer = null): void
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
    public function updateCategoryNodeOrder(int $idCategoryNode, int $position): void
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
    public function deleteNode(int $idNode, LocaleTransfer $localeTransfer, bool $deleteChildren = false): int
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
    public function getRootNodes(): array
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
    public function getTree(int $idCategory, LocaleTransfer $localeTransfer): array
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
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getChildren(int $idNode, LocaleTransfer $localeTransfer): ObjectCollection
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
    public function getParents(int $idNode, LocaleTransfer $localeTransfer, bool $excludeStartNode = true): array
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
    public function getTreeNodeChildrenByIdCategoryAndLocale(int $idCategory, LocaleTransfer $localeTransfer): array
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
    public function getSubTreeByIdCategoryNodeAndLocale(int $idCategoryNode, LocaleTransfer $localeTransfer): array
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
    public function rebuildClosureTable(): void
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
    public function generatePath(array $pathTokens): string
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
     * @param string $categoryKey
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getCategoryByKey(string $categoryKey, int $idLocale): CategoryTransfer
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
    public function touchCategoryActive(int $idCategory): void
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
    public function syncCategoryTemplate(): void
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
    public function findCategoryTemplateByName(string $name): ?CategoryTemplateTransfer
    {
        return $this->getFactory()
            ->createCategoryTemplateReader()
            ->findCategoryTemplateByName($name);
    }

    /**
     * @api
     *
     * @param string $name
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function hasFirstLevelChildrenByName(string $name, CategoryTransfer $categoryTransfer): bool
    {
        return $this->getFactory()
            ->createCategoryNodeChecker()
            ->hasFirstLevelChildrenByName($name, $categoryTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        return $this->getFactory()
            ->createCategory()
            ->getAllCategoryCollection($localeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $idsCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByCategoryIds(array $idsCategory, LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        return $this->getFactory()
            ->createCategory()
            ->getCategoryTransferCollectionByCategoryIds($idsCategory, $localeTransfer);
    }
}
