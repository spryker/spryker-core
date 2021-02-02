<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Category\Business\CategoryBusinessFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface getEntityManager()
 */
class CategoryFacade extends AbstractFacade implements CategoryFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllNodesByIdCategory($idCategory)
    {
        return $this->getFactory()
            ->createCategoryNodeReader()
            ->getAllNodesByIdCategory($idCategory);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer): void
    {
        $this
            ->getFactory()
            ->createCategoryCreator()
            ->createCategory($categoryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer): void
    {
        $this
            ->getFactory()
            ->createCategoryUpdater()
            ->updateCategory($categoryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreAssignment
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $currentStoreAssignment
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     *
     * @return void
     */
    public function updateCategoryStoreRelationWithMainChildrenPropagation(
        int $idCategory,
        StoreRelationTransfer $newStoreAssignment,
        ?StoreRelationTransfer $currentStoreAssignment = null
    ): void {
        $this->getFactory()
            ->createCategoryStoreUpdater()
            ->updateCategoryStoreRelationWithMainChildrenPropagation($idCategory, $newStoreAssignment, $currentStoreAssignment);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory): void
    {
        $this
            ->getFactory()
            ->createCategoryDeleter()
            ->deleteCategory($idCategory);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param int $position
     *
     * @return void
     */
    public function updateCategoryNodeOrder($idCategoryNode, $position): void
    {
        $this->getFactory()
            ->createCategoryNodeUpdater()
            ->updateCategoryNodeOrder($idCategoryNode, $position);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function checkSameLevelCategoryByNameExists(string $name, CategoryTransfer $categoryTransfer): bool
    {
        return $this->getRepository()->checkSameLevelCategoryByNameExists($name, $categoryTransfer);
    }

    /**
     * {@inheritDoc}
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
            ->createCategoryReader()
            ->getAllCategoryCollection($localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryById(int $idCategory): ?CategoryTransfer
    {
        return $this
            ->getFactory()
            ->createCategoryReader()
            ->findCategoryById($idCategory);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getNodePath(int $idNode, LocaleTransfer $localeTransfer): string
    {
        return $this->getRepository()->getCategoryNodePath($idNode, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getCategoryListUrl(): string
    {
        return $this->getFactory()->getConfig()->getDefaultRedirectUrl();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategory(CategoryCriteriaTransfer $categoryCriteriaTransfer): ?CategoryTransfer
    {
        return $this->getFactory()
            ->createCategoryReader()
            ->findCategory($categoryCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer $categoryNodeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getCategoryNodeUrls(CategoryNodeUrlFilterTransfer $categoryNodeFilterTransfer): array
    {
        return $this->getRepository()->getCategoryNodeUrls($categoryNodeFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getCategoryNodesWithRelativeNodesByCriteria(
        CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
    ): array {
        return $this->getRepository()
            ->getCategoryNodesWithRelativeNodesByCriteria($categoryNodeCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getCategoryNodesByCriteria(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): NodeCollectionTransfer
    {
        return $this->getRepository()->getCategoryNodesByCriteria($categoryNodeCriteriaTransfer);
    }
}
