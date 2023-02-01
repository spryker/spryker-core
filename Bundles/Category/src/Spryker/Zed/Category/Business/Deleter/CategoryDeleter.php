<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Deleter;

use Generated\Shared\Transfer\CategoryCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\CategoryCollectionResponseTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryDeleter implements CategoryDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected CategoryEntityManagerInterface $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Business\Deleter\CategoryRelationshipDeleterInterface
     */
    protected CategoryRelationshipDeleterInterface $categoryRelationshipDeleter;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected CategoryToEventFacadeInterface $eventFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Business\Deleter\CategoryRelationshipDeleterInterface $categoryRelationshipDeleter
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     */
    public function __construct(
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryRepositoryInterface $categoryRepository,
        CategoryRelationshipDeleterInterface $categoryRelationshipDeleter,
        CategoryToEventFacadeInterface $eventFacade
    ) {
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryRepository = $categoryRepository;
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
     * @param \Generated\Shared\Transfer\CategoryCollectionDeleteCriteriaTransfer $categoryCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    public function deleteCategoryCollection(
        CategoryCollectionDeleteCriteriaTransfer $categoryCollectionDeleteCriteriaTransfer
    ): CategoryCollectionResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($categoryCollectionDeleteCriteriaTransfer) {
            return $this->executeDeleteCategoryCollectionTransaction($categoryCollectionDeleteCriteriaTransfer);
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

        $this->triggerAfterDeleteEvents($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function triggerAfterDeleteEvents(CategoryTransfer $categoryTransfer): void
    {
        $this->eventFacade->trigger(CategoryEvents::CATEGORY_AFTER_DELETE, $categoryTransfer);

        $this->eventFacade->trigger(
            CategoryEvents::CATEGORY_AFTER_PUBLISH_DELETE,
            (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionDeleteCriteriaTransfer $categoryCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    protected function executeDeleteCategoryCollectionTransaction(
        CategoryCollectionDeleteCriteriaTransfer $categoryCollectionDeleteCriteriaTransfer
    ): CategoryCollectionResponseTransfer {
        $categoryCollectionTransfer = $this->categoryRepository->getCategoryDeleteCollection($categoryCollectionDeleteCriteriaTransfer);

        $categoryCollectionResponseTransfer = new CategoryCollectionResponseTransfer();

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $this->deleteCategory($categoryTransfer->getIdCategoryOrFail());

            $categoryCollectionResponseTransfer->addCategory($categoryTransfer);
        }

        return $categoryCollectionResponseTransfer;
    }
}
