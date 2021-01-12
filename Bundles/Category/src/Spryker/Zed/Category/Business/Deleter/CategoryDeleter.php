<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Deleter;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
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
     * @var \Spryker\Zed\Category\Business\Deleter\CategoryRelationshipDeleterInterface
     */
    protected $categoryRelationshipDeleter;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\Deleter\CategoryRelationshipDeleterInterface $categoryRelationshipDeleter
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     */
    public function __construct(
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryRelationshipDeleterInterface $categoryRelationshipDeleter,
        CategoryToEventFacadeInterface $eventFacade
    ) {
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryRelationshipDeleter = $categoryRelationshipDeleter;
        $this->eventFacade = $eventFacade;
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
        $categoryTransfer = (new CategoryTransfer())
            ->setIdCategory($idCategory);

        $this->eventFacade->trigger(CategoryEvents::CATEGORY_BEFORE_DELETE, $categoryTransfer);

        $this->categoryRelationshipDeleter->deleteCategoryRelationships($categoryTransfer);
        $this->categoryEntityManager->deleteCategory($idCategory);

        $this->eventFacade->trigger(CategoryEvents::CATEGORY_AFTER_DELETE, $categoryTransfer);
    }
}
