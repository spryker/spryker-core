<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function delete(int $idCategory): void
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
        $this
            ->getFactory()
            ->createNodeWriter()
            ->updateOrder($idCategoryNode, $position);
    }

    /**
     * {@inheritDoc}
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
     * @return void
     */
    public function syncCategoryTemplate(): void
    {
        $this->getFactory()
            ->createCategoryTemplateSync()
            ->syncFromConfig();
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
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer, ?string $storeName = null): CategoryCollectionTransfer
    {
        if ($storeName === null) {
            trigger_error('Pass the $storeName parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createCategory()
            ->getAllCategoryCollection($localeTransfer, $storeName);
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
}
