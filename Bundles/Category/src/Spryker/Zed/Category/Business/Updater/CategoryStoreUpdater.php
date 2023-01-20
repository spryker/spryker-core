<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer;
use Spryker\Zed\Category\Business\Exception\MissingCategoryException;
use Spryker\Zed\Category\Business\Reader\CategoryReaderInterface;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryStoreUpdater implements CategoryStoreUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\CategoryConfig;
     */
    protected $categoryConfig;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Business\Reader\CategoryReaderInterface
     */
    protected $categoryReader;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\Reader\CategoryReaderInterface $categoryReader
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Category\CategoryConfig $categoryConfig
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryReaderInterface $categoryReader,
        CategoryToEventFacadeInterface $eventFacade,
        CategoryConfig $categoryConfig
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryReader = $categoryReader;
        $this->eventFacade = $eventFacade;
        $this->categoryConfig = $categoryConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
     *
     * @return void
     */
    public function updateCategoryStoreRelationWithMainChildrenPropagation(
        UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($updateCategoryStoreRelationRequestTransfer) {
            $this->executeUpdateCategoryStoreRelationWithMainChildrenPropagationTransaction($updateCategoryStoreRelationRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
     *
     * @return void
     */
    protected function executeUpdateCategoryStoreRelationWithMainChildrenPropagationTransaction(
        UpdateCategoryStoreRelationRequestTransfer $updateCategoryStoreRelationRequestTransfer
    ): void {
        $idCategory = $updateCategoryStoreRelationRequestTransfer->getIdCategoryOrFail();
        $currentStoreAssignment = $updateCategoryStoreRelationRequestTransfer->getCurrentStoreAssignment();
        $newStoreAssignment = $updateCategoryStoreRelationRequestTransfer->getNewStoreAssignmentOrFail();

        $categoryTransfer = $this->getCurrentCategoryTransfer($idCategory);

        if (!$currentStoreAssignment) {
            $currentStoreAssignment = $categoryTransfer->getStoreRelationOrFail();
        }

        if ($categoryTransfer->getParentCategoryNode()) {
            $parentStoreRelationTransfer = $this->categoryRepository->getCategoryStoreRelationByIdCategoryNode(
                $categoryTransfer->getParentCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
            );
        }

        $storeIdsToAdd = $this->getStoreIdsToAdd(
            $currentStoreAssignment,
            $newStoreAssignment,
            $parentStoreRelationTransfer ?? null,
        );
        $storeIdsToDelete = $this->getStoreIdsToDelete(
            $currentStoreAssignment,
            $newStoreAssignment,
            $parentStoreRelationTransfer ?? null,
        );

        if ($storeIdsToAdd === [] && $storeIdsToDelete === []) {
            return;
        }

        $this->updateCategoryStoreRelations([$idCategory], $storeIdsToAdd, $storeIdsToDelete);
        $this->updateChildrenCategoryStoreRelations($categoryTransfer, $storeIdsToAdd, $storeIdsToDelete);

        $this->triggerCategoryTreePublishEvent($idCategory);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $existingStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $parentCategoryStoreRelationTransfer
     *
     * @return array<int>
     */
    protected function getStoreIdsToAdd(
        StoreRelationTransfer $existingStoreRelationTransfer,
        StoreRelationTransfer $newStoreRelationTransfer,
        ?StoreRelationTransfer $parentCategoryStoreRelationTransfer = null
    ): array {
        $storeIdsToAdd = array_diff($newStoreRelationTransfer->getIdStores(), $existingStoreRelationTransfer->getIdStores());

        if (!$parentCategoryStoreRelationTransfer) {
            return $storeIdsToAdd;
        }

        return $this->filterOutStoreIdsMissingInParentCategoryStoreRelation(
            $parentCategoryStoreRelationTransfer->getIdStores(),
            $storeIdsToAdd,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $existingStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $parentCategoryStoreRelationTransfer
     *
     * @return array<int>
     */
    protected function getStoreIdsToDelete(
        StoreRelationTransfer $existingStoreRelationTransfer,
        StoreRelationTransfer $newStoreRelationTransfer,
        ?StoreRelationTransfer $parentCategoryStoreRelationTransfer = null
    ): array {
        $storeIdsToDelete = array_diff($existingStoreRelationTransfer->getIdStores(), $newStoreRelationTransfer->getIdStores());
        if (!$parentCategoryStoreRelationTransfer) {
            return $storeIdsToDelete;
        }

        $missingParentCategoryStoreRelationIds = $this->getStoreIdsMissingInParentCategoryStoreRelation(
            $parentCategoryStoreRelationTransfer->getIdStores(),
            $existingStoreRelationTransfer->getIdStores(),
        );

        return array_unique(array_merge($storeIdsToDelete, $missingParentCategoryStoreRelationIds));
    }

    /**
     * @param array<int> $parentCategoryRelationStoreIds
     * @param array<int> $storeIds
     *
     * @return array<int>
     */
    protected function filterOutStoreIdsMissingInParentCategoryStoreRelation(array $parentCategoryRelationStoreIds, array $storeIds): array
    {
        return array_intersect($parentCategoryRelationStoreIds, $storeIds);
    }

    /**
     * @param array<int> $parentCategoryRelationStoreIds
     * @param array<int> $categoryRelationStoreIds
     *
     * @return array<int>
     */
    protected function getStoreIdsMissingInParentCategoryStoreRelation(array $parentCategoryRelationStoreIds, array $categoryRelationStoreIds): array
    {
        return array_diff($categoryRelationStoreIds, $parentCategoryRelationStoreIds);
    }

    /**
     * @param array<int> $categoryIds
     * @param array<int> $storeIdsToAdd
     * @param array<int> $storeIdsToDelete
     *
     * @return void
     */
    protected function updateCategoryStoreRelations(array $categoryIds, array $storeIdsToAdd, array $storeIdsToDelete): void
    {
        $this->categoryEntityManager->bulkCreateCategoryStoreRelationForStores($categoryIds, $storeIdsToAdd);
        $this->categoryEntityManager->bulkDeleteCategoryStoreRelationForStores($categoryIds, $storeIdsToDelete);
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function triggerCategoryTreePublishEvent(int $idCategory): void
    {
        $this->eventFacade->trigger(
            CategoryEvents::CATEGORY_TREE_PUBLISH,
            (new CategoryTransfer())->setIdCategory($idCategory),
        );
    }

    /**
     * @param int $idCategory
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function getCurrentCategoryTransfer(int $idCategory): CategoryTransfer
    {
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setIsMain(true);
        $categoryTransfer = $this->categoryReader->findCategory($categoryCriteriaTransfer);

        if (!$categoryTransfer) {
            throw new MissingCategoryException(sprintf('Could not find category for ID "%s"', $idCategory));
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param array<int> $storeIdsToAdd
     * @param array<int> $storeIdsToDelete
     *
     * @return void
     */
    protected function updateChildrenCategoryStoreRelations(CategoryTransfer $categoryTransfer, array $storeIdsToAdd, array $storeIdsToDelete): void
    {
        $categoryNodeChildCount = $this->categoryRepository->getCategoryNodeChildCountByParentNodeId($categoryTransfer);

        if (!$categoryNodeChildCount) {
            return;
        }

        $categoryReadChunkSize = $this->categoryConfig->getCategoryReadChunkSize();
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit($categoryReadChunkSize),
            );

        for ($offset = 0; $offset <= $categoryNodeChildCount; $offset += $categoryReadChunkSize) {
            $categoryCriteriaTransfer->getPaginationOrFail()->setOffset($offset);
            $descendantCategoryIds = $this->categoryRepository->getDescendantCategoryIdsByIdCategory(
                $categoryTransfer,
                $categoryCriteriaTransfer,
            );

            $this->updateCategoryStoreRelations($descendantCategoryIds, $storeIdsToAdd, $storeIdsToDelete);
        }
    }
}
