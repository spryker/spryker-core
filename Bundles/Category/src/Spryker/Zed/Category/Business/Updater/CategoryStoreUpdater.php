<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Category\Business\Exception\MissingCategoryException;
use Spryker\Zed\Category\Business\Reader\CategoryReaderInterface;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryStoreUpdater implements CategoryStoreUpdaterInterface
{
    use TransactionTrait;

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
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryReaderInterface $categoryReader,
        CategoryToEventFacadeInterface $eventFacade
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryReader = $categoryReader;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreAssignment
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $currentStoreAssignment
     *
     * @return void
     */
    public function updateCategoryStoreRelationWithMainChildrenPropagation(
        int $idCategory,
        StoreRelationTransfer $newStoreAssignment,
        ?StoreRelationTransfer $currentStoreAssignment = null
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($idCategory, $newStoreAssignment, $currentStoreAssignment) {
            $this->executeUpdateCategoryStoreRelationWithMainChildrenPropagationTransaction($idCategory, $newStoreAssignment, $currentStoreAssignment);
        });
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreAssignment
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $currentStoreAssignment
     *
     * @return void
     */
    protected function executeUpdateCategoryStoreRelationWithMainChildrenPropagationTransaction(
        int $idCategory,
        StoreRelationTransfer $newStoreAssignment,
        ?StoreRelationTransfer $currentStoreAssignment = null
    ): void {
        $categoryTransfer = $this->getCurrentCategoryTransfer($idCategory);
        if (!$currentStoreAssignment) {
            $currentStoreAssignment = $categoryTransfer->getStoreRelationOrFail();
        }

        if ($categoryTransfer->getParentCategoryNode()) {
            $parentStoreRelationTransfer = $this->categoryRepository->getCategoryStoreRelationByIdCategoryNode(
                $categoryTransfer->getParentCategoryNodeOrFail()->getIdCategoryNodeOrFail()
            );
        }

        $storeIdsToAdd = $this->getStoreIdsToAdd(
            $currentStoreAssignment,
            $newStoreAssignment,
            $parentStoreRelationTransfer ?? null
        );
        $storeIdsToDelete = $this->getStoreIdsToDelete(
            $currentStoreAssignment,
            $newStoreAssignment,
            $parentStoreRelationTransfer ?? null
        );

        if ($storeIdsToAdd === [] && $storeIdsToDelete === []) {
            return;
        }

        $this->updateCategoryStoreRelations($idCategory, $storeIdsToAdd, $storeIdsToDelete);
        $this->updateChildrenCategoryStoreRelations($categoryTransfer, $storeIdsToAdd, $storeIdsToDelete);

        $this->triggerCategoryTreePublishEvent($idCategory);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $existingStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $parentCategoryStoreRelationTransfer
     *
     * @return int[]
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
            $storeIdsToAdd
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $existingStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $parentCategoryStoreRelationTransfer
     *
     * @return int[]
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
            $existingStoreRelationTransfer->getIdStores()
        );

        return array_unique(array_merge($storeIdsToDelete, $missingParentCategoryStoreRelationIds));
    }

    /**
     * @param int[] $parentCategoryRelationStoreIds
     * @param int[] $storeIds
     *
     * @return int[]
     */
    protected function filterOutStoreIdsMissingInParentCategoryStoreRelation(array $parentCategoryRelationStoreIds, array $storeIds): array
    {
        return array_intersect($parentCategoryRelationStoreIds, $storeIds);
    }

    /**
     * @param int[] $parentCategoryRelationStoreIds
     * @param int[] $categoryRelationStoreIds
     *
     * @return int[]
     */
    protected function getStoreIdsMissingInParentCategoryStoreRelation(array $parentCategoryRelationStoreIds, array $categoryRelationStoreIds): array
    {
        return array_diff($categoryRelationStoreIds, $parentCategoryRelationStoreIds);
    }

    /**
     * @param int $idCategory
     * @param int[] $storeIdsToAdd
     * @param int[] $storeIdsToDelete
     *
     * @return void
     */
    protected function updateCategoryStoreRelations(int $idCategory, array $storeIdsToAdd, array $storeIdsToDelete): void
    {
        $this->categoryEntityManager->createCategoryStoreRelationForStores($idCategory, $storeIdsToAdd);
        $this->categoryEntityManager->deleteCategoryStoreRelationForStores($idCategory, $storeIdsToDelete);
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
            (new CategoryTransfer())->setIdCategory($idCategory)
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
            ->setIsMain(true)
            ->setWithChildrenRecursively(true);
        $categoryTransfer = $this->categoryReader->findCategory($categoryCriteriaTransfer);

        if (!$categoryTransfer) {
            throw new MissingCategoryException(sprintf('Could not find category for ID "%s"', $idCategory));
        }

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param int[] $storeIdsToAdd
     * @param int[] $storeIdsToDelete
     *
     * @return void
     */
    protected function updateChildrenCategoryStoreRelations(CategoryTransfer $categoryTransfer, array $storeIdsToAdd, array $storeIdsToDelete): void
    {
        if (!$categoryTransfer->getNodeCollection() || $categoryTransfer->getNodeCollectionOrFail()->getNodes()->count() === 0) {
            return;
        }

        foreach ($categoryTransfer->getNodeCollectionOrFail()->getNodes() as $nodeTransfer) {
            if (!$nodeTransfer->getIsMain()) {
                continue;
            }
            $this->updateMainChildCategoryStoreRelation($nodeTransfer->getChildrenNodesOrFail(), $storeIdsToAdd, $storeIdsToDelete);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     * @param int[] $storeIdsToAdd
     * @param int[] $storeIdsToDelete
     *
     * @return void
     */
    protected function updateMainChildCategoryStoreRelation(NodeCollectionTransfer $nodeCollectionTransfer, array $storeIdsToAdd, array $storeIdsToDelete): void
    {
        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            if (!$nodeTransfer->getIsMain()) {
                continue;
            }
            $this->updateCategoryStoreRelations($nodeTransfer->getFkCategoryOrFail(), $storeIdsToAdd, $storeIdsToDelete);

            if (!$nodeTransfer->getChildrenNodesOrFail()->getNodes()->count()) {
                continue;
            }

            $this->updateMainChildCategoryStoreRelation($nodeTransfer->getChildrenNodesOrFail(), $storeIdsToAdd, $storeIdsToDelete);
        }
    }
}
