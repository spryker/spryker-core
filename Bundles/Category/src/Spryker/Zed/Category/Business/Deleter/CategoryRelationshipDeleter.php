<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Deleter;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryRelationshipDeleter implements CategoryRelationshipDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Business\Deleter\CategoryAttributeDeleterInterface
     */
    protected $categoryAttributeDeleter;

    /**
     * @var \Spryker\Zed\Category\Business\Deleter\CategoryUrlDeleterInterface
     */
    protected $categoryUrlDeleter;

    /**
     * @var \Spryker\Zed\Category\Business\Deleter\CategoryNodeDeleterInterface
     */
    protected $categoryNodeDeleter;

    /**
     * @var \Spryker\Zed\Category\Business\Deleter\CategoryStoreDeleterInterface
     */
    protected $categoryStoreDeleter;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationDeletePluginInterface[]
     */
    protected $categoryRelationDeletePlugins;

    /**
     * @param \Spryker\Zed\Category\Business\Deleter\CategoryAttributeDeleterInterface $categoryAttributeDeleter
     * @param \Spryker\Zed\Category\Business\Deleter\CategoryUrlDeleterInterface $categoryUrlDeleter
     * @param \Spryker\Zed\Category\Business\Deleter\CategoryNodeDeleterInterface $categoryNodeDeleter
     * @param \Spryker\Zed\Category\Business\Deleter\CategoryStoreDeleterInterface $categoryStoreDeleter
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationDeletePluginInterface[] $categoryRelationDeletePlugins
     */
    public function __construct(
        CategoryAttributeDeleterInterface $categoryAttributeDeleter,
        CategoryUrlDeleterInterface $categoryUrlDeleter,
        CategoryNodeDeleterInterface $categoryNodeDeleter,
        CategoryStoreDeleterInterface $categoryStoreDeleter,
        array $categoryRelationDeletePlugins
    ) {
        $this->categoryAttributeDeleter = $categoryAttributeDeleter;
        $this->categoryUrlDeleter = $categoryUrlDeleter;
        $this->categoryNodeDeleter = $categoryNodeDeleter;
        $this->categoryStoreDeleter = $categoryStoreDeleter;
        $this->categoryRelationDeletePlugins = $categoryRelationDeletePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function deleteCategoryRelationships(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            $this->executeDeleteCategoryRelationshipsTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeDeleteCategoryRelationshipsTransaction(CategoryTransfer $categoryTransfer): void
    {
        $idCategory = $categoryTransfer->getIdCategoryOrFail();

        $this->categoryAttributeDeleter->deleteCategoryLocalizedAttributes($idCategory);
        $this->categoryUrlDeleter->deleteCategoryUrlsForCategory($idCategory);
        $this->categoryNodeDeleter->deleteCategoryNodes($idCategory);
        $this->categoryNodeDeleter->deleteCategoryExtraParentNodes($idCategory);
        $this->categoryStoreDeleter->deleteCategoryStoreRelations($idCategory);

        $this->executeCategoryRelationDeletePlugins($idCategory);
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function executeCategoryRelationDeletePlugins(int $idCategory): void
    {
        foreach ($this->categoryRelationDeletePlugins as $categoryRelationDeletePlugin) {
            $categoryRelationDeletePlugin->delete($idCategory);
        }
    }
}
