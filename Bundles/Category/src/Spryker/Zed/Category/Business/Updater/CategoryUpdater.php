<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryUpdater implements CategoryUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryRelationshipUpdaterInterface
     */
    protected $categoryRelationshipUpdater;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUpdateAfterPluginInterface[]
     */
    protected $categoryUpdateAfterPlugins;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\Updater\CategoryRelationshipUpdaterInterface $categoryRelationshipUpdater
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUpdateAfterPluginInterface[] $categoryUpdateAfterPlugins
     */
    public function __construct(
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryRelationshipUpdaterInterface $categoryRelationshipUpdater,
        CategoryToEventFacadeInterface $eventFacade,
        array $categoryUpdateAfterPlugins
    ) {
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryRelationshipUpdater = $categoryRelationshipUpdater;
        $this->eventFacade = $eventFacade;
        $this->categoryUpdateAfterPlugins = $categoryUpdateAfterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            $this->executeUpdateCategoryTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeUpdateCategoryTransaction(CategoryTransfer $categoryTransfer): void
    {
        $this->eventFacade->trigger(CategoryEvents::CATEGORY_BEFORE_UPDATE, $categoryTransfer);

        $this->categoryRelationshipUpdater->updateCategoryRelationships($categoryTransfer);
        $this->categoryEntityManager->updateCategory($categoryTransfer);

        $this->eventFacade->trigger(CategoryEvents::CATEGORY_AFTER_UPDATE, $categoryTransfer);
        $this->eventFacade->trigger(
            CategoryEvents::CATEGORY_AFTER_PUBLISH_UPDATE,
            (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory())
        );

        $this->executeCategoryUpdateAfterPlugins($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCategoryUpdateAfterPlugins(CategoryTransfer $categoryTransfer): void
    {
        foreach ($this->categoryUpdateAfterPlugins as $categoryUpdateAfterPlugin) {
            $categoryUpdateAfterPlugin->execute($categoryTransfer);
        }
    }
}
