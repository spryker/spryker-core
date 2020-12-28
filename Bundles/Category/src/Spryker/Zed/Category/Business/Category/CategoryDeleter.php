<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Category;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Business\CategoryAttribute\CategoryAttributeDeleterInterface;
use Spryker\Zed\Category\Business\CategoryNode\CategoryNodeDeleterInterface;
use Spryker\Zed\Category\Business\CategoryUrl\CategoryUrlDeleterInterface;
use Spryker\Zed\Category\Business\Event\CategoryEventTriggerManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryDeleter implements CategoryDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryAttribute\CategoryAttributeDeleterInterface
     */
    protected $categoryAttributeDeleter;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryUrl\CategoryUrlDeleterInterface
     */
    protected $categoryUrlDeleter;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryNode\CategoryNodeDeleterInterface
     */
    protected $categoryNodeDeleter;

    /**
     * @var \Spryker\Zed\Category\Business\Event\CategoryEventTriggerManagerInterface
     */
    protected $categoryEventTriggerManager;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationDeletePluginInterface[]
     */
    protected $categoryRelationDeletePlugins;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\CategoryAttribute\CategoryAttributeDeleterInterface $categoryAttributeDeleter
     * @param \Spryker\Zed\Category\Business\CategoryUrl\CategoryUrlDeleterInterface $categoryUrlDeleter
     * @param \Spryker\Zed\Category\Business\CategoryNode\CategoryNodeDeleterInterface $categoryNodeDeleter
     * @param \Spryker\Zed\Category\Business\Event\CategoryEventTriggerManagerInterface $eventTriggerManager
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryRelationDeletePluginInterface[] $categoryRelationDeletePlugins
     */
    public function __construct(
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryAttributeDeleterInterface $categoryAttributeDeleter,
        CategoryUrlDeleterInterface $categoryUrlDeleter,
        CategoryNodeDeleterInterface $categoryNodeDeleter,
        CategoryEventTriggerManagerInterface $eventTriggerManager,
        array $categoryRelationDeletePlugins
    ) {
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryAttributeDeleter = $categoryAttributeDeleter;
        $this->categoryUrlDeleter = $categoryUrlDeleter;
        $this->categoryNodeDeleter = $categoryNodeDeleter;
        $this->categoryEventTriggerManager = $eventTriggerManager;
        $this->categoryRelationDeletePlugins = $categoryRelationDeletePlugins;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory(int $idCategory): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idCategory) {
            $this->executeDeleteCategoryTransaction($idCategory);
        });
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function executeDeleteCategoryTransaction(int $idCategory): void
    {
        $categoryTransfer = (new CategoryTransfer())->setIdCategory($idCategory);

        $this->categoryEventTriggerManager->triggerCategoryBeforeDeleteEvent($categoryTransfer);

        $this->categoryAttributeDeleter->deleteCategoryLocalizedAttributes($idCategory);
        $this->categoryUrlDeleter->deleteCategoryUrlsForCategory($idCategory);
        $this->categoryNodeDeleter->deleteCategoryNodes($idCategory);
        $this->categoryNodeDeleter->deleteCategoryExtraParentNodes($idCategory);
        $this->categoryEntityManager->deleteCategoryStoreRelations($idCategory);

        $this->executeCategoryRelationDeletePlugins($idCategory);

        $this->categoryEntityManager->deleteCategory($idCategory);

        $this->categoryEventTriggerManager->triggerCategoryAfterDeleteEvent($categoryTransfer);
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
