<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Creator;

use Generated\Shared\Transfer\CategoryTransfer;
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
     * @var \Spryker\Zed\Category\Business\Creator\CategoryRelationshipCreatorInterface
     */
    protected $categoryRelationshipCreator;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface[]
     */
    protected $categoryPostCreatePlugins;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\Creator\CategoryRelationshipCreatorInterface $categoryRelationshipCreator
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface[] $categoryPostCreatePlugins
     */
    public function __construct(
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryRelationshipCreatorInterface $categoryRelationshipCreator,
        CategoryToEventFacadeInterface $eventFacade,
        array $categoryPostCreatePlugins
    ) {
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryRelationshipCreator = $categoryRelationshipCreator;
        $this->eventFacade = $eventFacade;
        $this->categoryPostCreatePlugins = $categoryPostCreatePlugins;
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
        $this->eventFacade->trigger(CategoryEvents::CATEGORY_BEFORE_CREATE, $categoryTransfer);

        $this->categoryEntityManager->createCategory($categoryTransfer);
        $this->categoryRelationshipCreator->createCategoryRelationships($categoryTransfer);

        $this->eventFacade->trigger(CategoryEvents::CATEGORY_AFTER_CREATE, $categoryTransfer);

        $this->executePostCreatePlugins($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executePostCreatePlugins(CategoryTransfer $categoryTransfer): void
    {
        foreach ($this->categoryPostCreatePlugins as $categoryPostCreatePlugin) {
            $categoryPostCreatePlugin->execute($categoryTransfer);
        }
    }
}
