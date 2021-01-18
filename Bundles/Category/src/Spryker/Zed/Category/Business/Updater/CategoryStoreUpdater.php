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
use Spryker\Zed\Category\Business\Model\CategoryReaderInterface;
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
     * @var \Spryker\Zed\Category\Business\Model\CategoryReaderInterface
     */
    protected $categoryReader;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\Model\CategoryReaderInterface $categoryReader
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
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     *
     * @return void
     */
    protected function executeUpdateCategoryStoreRelationWithMainChildrenPropagationTransaction(
        int $idCategory,
        StoreRelationTransfer $newStoreAssignment,
        ?StoreRelationTransfer $currentStoreAssignment = null
    ): void {
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setIsMain(true)
            ->setWithChildrenRecursively(true);
        $categoryTransfer = $this->categoryReader->findCategory($categoryCriteriaTransfer);
        if (!$categoryTransfer) {
            throw new MissingCategoryException(sprintf('Could not find category for ID "%s"', $idCategory));
        }

        if (!$currentStoreAssignment) {
            $currentStoreAssignment = $categoryTransfer->getStoreRelationOrFail();
        }

        $storeIdsToAdd = $this->getStoreIdsToAdd($currentStoreAssignment, $newStoreAssignment);
        $storeIdsToAdd = $this->filterOutStoreIdsMissingInParentCategoryStoreRelation($idCategory, $storeIdsToAdd);
        $storeIdsToDelete = $this->getStoreIdsToDelete($currentStoreAssignment, $newStoreAssignment);

        $this->updateCategoryStoreRelations($idCategory, $storeIdsToAdd, $storeIdsToDelete);

        if (!$categoryTransfer->getNodeCollection()) {
            $this->triggerCategoryTreePublishEvent($idCategory);

            return;
        }

        foreach ($categoryTransfer->getNodeCollection()->getNodes() as $nodeTransfer) {
            if ($nodeTransfer->getIsMain()) {
                $this->updateMainChildCategoryStoreRelation($nodeTransfer->getChildrenNodes(), $storeIdsToAdd, $storeIdsToDelete);
            }
        }

        $this->triggerCategoryTreePublishEvent($idCategory);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $existingStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreRelationTransfer
     *
     * @return int[]
     */
    protected function getStoreIdsToDelete(
        StoreRelationTransfer $existingStoreRelationTransfer,
        StoreRelationTransfer $newStoreRelationTransfer
    ): array {
        return array_diff($existingStoreRelationTransfer->getIdStores(), $newStoreRelationTransfer->getIdStores());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $existingStoreRelationTransfer
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $newStoreRelationTransfer
     *
     * @return int[]
     */
    protected function getStoreIdsToAdd(
        StoreRelationTransfer $existingStoreRelationTransfer,
        StoreRelationTransfer $newStoreRelationTransfer
    ): array {
        return array_diff($newStoreRelationTransfer->getIdStores(), $existingStoreRelationTransfer->getIdStores());
    }

    /**
     * @param int $idCategory
     * @param int[] $storeIds
     *
     * @return int[]
     */
    protected function filterOutStoreIdsMissingInParentCategoryStoreRelation(int $idCategory, array $storeIds): array
    {
        return array_filter($storeIds, function (int $idStore) use ($idCategory) {
            return $this->categoryRepository->isParentCategoryHasRelationToStore($idCategory, $idStore);
        });
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
            $this->updateCategoryStoreRelations($nodeTransfer->getFkCategory(), $storeIdsToAdd, $storeIdsToDelete);

            if (!$nodeTransfer->getChildrenNodes()->getNodes()->count()) {
                continue;
            }

            $this->updateMainChildCategoryStoreRelation($nodeTransfer->getChildrenNodes(), $storeIdsToAdd, $storeIdsToDelete);
        }
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
}
