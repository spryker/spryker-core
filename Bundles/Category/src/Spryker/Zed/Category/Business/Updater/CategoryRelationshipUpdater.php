<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer;
use Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSyncInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryRelationshipUpdater implements CategoryRelationshipUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryNodeUpdaterInterface
     */
    protected $categoryNodeUpdater;

    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryUrlUpdaterInterface
     */
    protected $categoryUrlUpdater;

    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryAttributeUpdaterInterface
     */
    protected $categoryAttributeUpdater;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSyncInterface
     */
    protected $categoryTemplateSync;

    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryStoreRelationUpdaterInterface
     */
    protected $categoryStoreRelationUpdater;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface[]
     */
    protected $categoryRelationUpdatePlugins;

    /**
     * @param \Spryker\Zed\Category\Business\Updater\CategoryNodeUpdaterInterface $categoryNodeUpdater
     * @param \Spryker\Zed\Category\Business\Updater\CategoryUrlUpdaterInterface $categoryUrlUpdater
     * @param \Spryker\Zed\Category\Business\Updater\CategoryAttributeUpdaterInterface $categoryAttributeUpdater
     * @param \Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSyncInterface $categoryTemplateSync
     * @param \Spryker\Zed\Category\Business\Updater\CategoryStoreRelationUpdaterInterface $categoryStoreRelationUpdater
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface[] $categoryRelationUpdatePlugins
     */
    public function __construct(
        CategoryNodeUpdaterInterface $categoryNodeUpdater,
        CategoryUrlUpdaterInterface $categoryUrlUpdater,
        CategoryAttributeUpdaterInterface $categoryAttributeUpdater,
        CategoryTemplateSyncInterface $categoryTemplateSync,
        CategoryStoreRelationUpdaterInterface $categoryStoreRelationUpdater,
        array $categoryRelationUpdatePlugins
    ) {
        $this->categoryNodeUpdater = $categoryNodeUpdater;
        $this->categoryUrlUpdater = $categoryUrlUpdater;
        $this->categoryAttributeUpdater = $categoryAttributeUpdater;
        $this->categoryTemplateSync = $categoryTemplateSync;
        $this->categoryStoreRelationUpdater = $categoryStoreRelationUpdater;
        $this->categoryRelationUpdatePlugins = $categoryRelationUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryRelationships(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            $this->executeUpdateCategoryRelationshipsTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeUpdateCategoryRelationshipsTransaction(CategoryTransfer $categoryTransfer): void
    {
        $this->categoryTemplateSync->syncFromConfig();

        $this->executeCategoryRelationUpdatePlugins($categoryTransfer);

        $this->categoryNodeUpdater->updateCategoryNode($categoryTransfer);
        $this->categoryAttributeUpdater->updateCategoryAttributes($categoryTransfer);
        $this->categoryUrlUpdater->updateCategoryUrl($categoryTransfer);
        $this->categoryNodeUpdater->updateExtraParentCategoryNodes($categoryTransfer);

        $this->handleStoreRelationUpdate($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCategoryRelationUpdatePlugins(CategoryTransfer $categoryTransfer): void
    {
        foreach ($this->categoryRelationUpdatePlugins as $categoryRelationUpdatePlugin) {
            $categoryRelationUpdatePlugin->update($categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function handleStoreRelationUpdate(CategoryTransfer $categoryTransfer): void
    {
        if ($categoryTransfer->getStoreRelation() === null) {
            return;
        }

        $updateCategoryStoreRelationRequestTransfer = (new UpdateCategoryStoreRelationRequestTransfer())
            ->setIdCategory($categoryTransfer->getIdCategoryOrFail())
            ->setNewStoreAssignment($categoryTransfer->getStoreRelationOrFail());

        $this->categoryStoreRelationUpdater->updateCategoryStoreRelation($updateCategoryStoreRelationRequestTransfer);
    }
}
