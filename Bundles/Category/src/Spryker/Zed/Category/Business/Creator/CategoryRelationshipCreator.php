<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Creator;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSyncInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryRelationshipCreator implements CategoryRelationshipCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Business\Creator\CategoryNodeCreatorInterface
     */
    protected $categoryNodeCreator;

    /**
     * @var \Spryker\Zed\Category\Business\Creator\CategoryAttributeCreatorInterface
     */
    protected $categoryAttributeCreator;

    /**
     * @var \Spryker\Zed\Category\Business\Creator\CategoryUrlCreatorInterface
     */
    protected $categoryUrlCreator;

    /**
     * @var \Spryker\Zed\Category\Business\Creator\CategoryStoreCreatorInterface
     */
    protected $categoryStoreCreator;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSyncInterface
     */
    protected $categoryTemplateSync;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface[]
     */
    protected $categoryRelationUpdatePlugins;

    /**
     * @param \Spryker\Zed\Category\Business\Creator\CategoryNodeCreatorInterface $categoryNodeCreator
     * @param \Spryker\Zed\Category\Business\Creator\CategoryAttributeCreatorInterface $categoryAttributeCreator
     * @param \Spryker\Zed\Category\Business\Creator\CategoryUrlCreatorInterface $categoryUrlCreator
     * @param \Spryker\Zed\Category\Business\Creator\CategoryStoreCreatorInterface $categoryStoreCreator
     * @param \Spryker\Zed\Category\Business\Model\CategoryTemplate\CategoryTemplateSyncInterface $categoryTemplateSync
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationUpdatePluginInterface[] $categoryRelationUpdatePlugins
     */
    public function __construct(
        CategoryNodeCreatorInterface $categoryNodeCreator,
        CategoryAttributeCreatorInterface $categoryAttributeCreator,
        CategoryUrlCreatorInterface $categoryUrlCreator,
        CategoryStoreCreatorInterface $categoryStoreCreator,
        CategoryTemplateSyncInterface $categoryTemplateSync,
        array $categoryRelationUpdatePlugins = []
    ) {
        $this->categoryNodeCreator = $categoryNodeCreator;
        $this->categoryAttributeCreator = $categoryAttributeCreator;
        $this->categoryUrlCreator = $categoryUrlCreator;
        $this->categoryStoreCreator = $categoryStoreCreator;
        $this->categoryTemplateSync = $categoryTemplateSync;
        $this->categoryRelationUpdatePlugins = $categoryRelationUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function createCategoryRelationships(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            $this->executeCreateCategoryRelationshipsTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCreateCategoryRelationshipsTransaction(CategoryTransfer $categoryTransfer): void
    {
        $this->categoryTemplateSync->syncFromConfig();

        $this->categoryStoreCreator->createCategoryStoreRelations($categoryTransfer);
        $this->categoryNodeCreator->createCategoryNode($categoryTransfer);
        $this->categoryNodeCreator->createExtraParentsCategoryNodes($categoryTransfer);
        $this->categoryAttributeCreator->createCategoryLocalizedAttributes($categoryTransfer);
        $this->categoryUrlCreator->createCategoryUrl($categoryTransfer);

        $this->executeCategoryRelationUpdatePlugins($categoryTransfer);
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
}
