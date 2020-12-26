<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Category;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Business\CategoryAttribute\CategoryAttributeCreatorInterface;
use Spryker\Zed\Category\Business\CategoryNode\CategoryNodeCreatorInterface;
use Spryker\Zed\Category\Business\CategoryUrl\CategoryUrlCreatorInterface;
use Spryker\Zed\Category\Business\PluginExecutor\CategoryPluginExecutorInterface;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryCreator implements CategoryCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryNode\CategoryNodeCreatorInterface
     */
    protected $categoryNodeCreator;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryAttribute\CategoryAttributeCreatorInterface
     */
    protected $categoryAttributeCreator;

    /**
     * @var \Spryker\Zed\Category\Business\CategoryUrl\CategoryUrlCreatorInterface
     */
    protected $categoryUrlCreator;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\Category\Business\PluginExecutor\CategoryPluginExecutorInterface
     */
    protected $categoryPluginExecutor;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\CategoryNode\CategoryNodeCreatorInterface $categoryNodeCreator
     * @param \Spryker\Zed\Category\Business\CategoryAttribute\CategoryAttributeCreatorInterface $categoryAttributeCreator
     * @param \Spryker\Zed\Category\Business\CategoryUrl\CategoryUrlCreatorInterface $categoryUrlCreator
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Category\Business\PluginExecutor\CategoryPluginExecutorInterface $categoryPluginExecutor
     */
    public function __construct(
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryNodeCreatorInterface $categoryNodeCreator,
        CategoryAttributeCreatorInterface $categoryAttributeCreator,
        CategoryUrlCreatorInterface $categoryUrlCreator,
        CategoryToEventFacadeInterface $eventFacade,
        CategoryPluginExecutorInterface $categoryPluginExecutor
    ) {
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryNodeCreator = $categoryNodeCreator;
        $this->categoryAttributeCreator = $categoryAttributeCreator;
        $this->categoryUrlCreator = $categoryUrlCreator;
        $this->eventFacade = $eventFacade;
        $this->categoryPluginExecutor = $categoryPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function createCategory(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer): void {
            $this->executeCreateCategoryTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCreateCategoryTransaction(CategoryTransfer $categoryTransfer): void
    {
        $this->triggerEvent(CategoryEvents::CATEGORY_BEFORE_UPDATE, $categoryTransfer);

        $this->categoryEntityManager->createCategory($categoryTransfer);
        if ($categoryTransfer->getStoreRelation()) {
            $this->categoryEntityManager->createCategoryStoreRelationForStores(
                $categoryTransfer->getIdCategory(),
                $categoryTransfer->getStoreRelation()->getIdStores()
            );
        }

        $this->categoryNodeCreator->createCategoryNode($categoryTransfer);
        $this->categoryNodeCreator->createExtraParentsCategoryNodes($categoryTransfer);
        $this->categoryAttributeCreator->createCategoryLocalizedAttributes($categoryTransfer);
        $this->categoryUrlCreator->createCategoryUrl($categoryTransfer);

        $this->categoryPluginExecutor->executeCategoryRelationUpdatePlugins($categoryTransfer);
        $this->triggerEvent(CategoryEvents::CATEGORY_AFTER_CREATE, $categoryTransfer);
        $this->categoryPluginExecutor->executePostCreatePlugins($categoryTransfer);
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function triggerEvent(string $eventName, CategoryTransfer $categoryTransfer): void
    {
        if ($this->eventFacade === null) {
            return;
        }

        $this->eventFacade->trigger($eventName, $categoryTransfer);
    }
}
