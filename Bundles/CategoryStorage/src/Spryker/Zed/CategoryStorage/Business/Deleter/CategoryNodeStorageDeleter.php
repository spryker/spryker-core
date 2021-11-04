<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Deleter;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface;

class CategoryNodeStorageDeleter implements CategoryNodeStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface
     */
    protected $categoryStorageEntityManager;

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface
     */
    protected $categoryNodeExtractor;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface $categoryStorageEntityManager
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface $categoryNodeExtractor
     */
    public function __construct(
        CategoryStorageEntityManagerInterface $categoryStorageEntityManager,
        CategoryStorageToCategoryFacadeInterface $categoryFacade,
        CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodeExtractorInterface $categoryNodeExtractor
    ) {
        $this->categoryStorageEntityManager = $categoryStorageEntityManager;
        $this->categoryFacade = $categoryFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodeExtractor = $categoryNodeExtractor;
    }

    /**
     * @param array<array<array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer>>> $categoryNodeStorageTransferTreesIndexedByLocaleAndStore
     * @param array<int> $categoryNodeIds
     *
     * @return void
     */
    public function deleteMissingCategoryNodeStorage(array $categoryNodeStorageTransferTreesIndexedByLocaleAndStore, array $categoryNodeIds): void
    {
        foreach ($categoryNodeStorageTransferTreesIndexedByLocaleAndStore as $storeName => $categoryNodeStorageTransferTreesIndexedByLocale) {
            foreach ($categoryNodeStorageTransferTreesIndexedByLocale as $localeName => $categoryNodeStorageTransfers) {
                $this->deleteMissingCategoryNodeStorageForLocaleAndStore($categoryNodeIds, $categoryNodeStorageTransfers, $localeName, $storeName);
            }
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryNodeEvents(array $eventEntityTransfers): void
    {
        $categoryNodeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->deleteCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * @param array<int> $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollection(array $categoryNodeIds): void
    {
        $this->categoryStorageEntityManager->deleteCategoryNodeStorageByCategoryNodeIds($categoryNodeIds);
    }

    /**
     * @param array<int> $categoryNodeIds
     * @param array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer> $categoryNodeStorageTransfers
     * @param string $localeName
     * @param string $storeName
     *
     * @return void
     */
    protected function deleteMissingCategoryNodeStorageForLocaleAndStore(
        array $categoryNodeIds,
        array $categoryNodeStorageTransfers,
        string $localeName,
        string $storeName
    ): void {
        $categoryNodeIdsToDelete = $categoryNodeIds;
        if ($categoryNodeStorageTransfers !== []) {
            $categoryNodeIdsToDelete = array_diff($categoryNodeIds, array_keys($categoryNodeStorageTransfers));
        }

        $this->categoryStorageEntityManager->deleteCategoryNodeStoragesForStoreAndLocale($categoryNodeIdsToDelete, $localeName, $storeName);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryNodeCriteria(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): void
    {
        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodes($categoryNodeCriteriaTransfer);
        $categoryNodeIds = $this->categoryNodeExtractor->extractCategoryNodeIdsFromNodeCollection($nodeCollectionTransfer);

        $this->deleteCategoryNodeStorageCollection($categoryNodeIds);
    }
}
