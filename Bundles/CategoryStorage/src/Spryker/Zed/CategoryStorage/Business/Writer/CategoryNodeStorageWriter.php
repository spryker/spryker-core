<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Writer;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CategoryNodeTreeElementCriteriaTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface;

class CategoryNodeStorageWriter implements CategoryNodeStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface
     */
    protected $categoryStorageEntityManager;

    /**
     * @var \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface
     */
    protected $categoryStorageNodeTreeBuilder;

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface $categoryStorageEntityManager
     * @param \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     */
    public function __construct(
        CategoryStorageEntityManagerInterface $categoryStorageEntityManager,
        CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder,
        CategoryStorageToCategoryFacadeInterface $categoryFacade,
        CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
    ) {
        $this->categoryStorageEntityManager = $categoryStorageEntityManager;
        $this->categoryStorageNodeTreeBuilder = $categoryStorageNodeTreeBuilder;
        $this->categoryFacade = $categoryFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, SpyCategoryStoreTableMap::COL_FK_CATEGORY);
        $categoryNodeIds = $this->categoryFacade->getCategoryNodeIdsByCategoryIds($categoryIds);

        $this->writeCollection($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryStorePublishEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);
        $categoryNodeIds = $this->categoryFacade->getCategoryNodeIdsByCategoryIds($categoryIds);

        $this->writeCollection($categoryNodeIds);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function writeCollection(array $categoryNodeIds): void
    {
        $nodeTransfers = $this->categoryFacade->getAllActiveCategoryNodeTreeMenuElementsByCategoryNodeIds(
            (new CategoryNodeTreeElementCriteriaTransfer())->setCategoryNodeIds($categoryNodeIds)
        );

        $categoryNodeStorageTransferTreesIndexedByLocaleAndStore = $this->categoryStorageNodeTreeBuilder
            ->buildCategoryNodeStorageTransferTreesForLocaleAndStore(
                $categoryNodeIds,
                $nodeTransfers
            );

        $this->storeData($categoryNodeStorageTransferTreesIndexedByLocaleAndStore);
        $this->deleteMissingCategoryNodeStorage($nodeTransfers, $categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][][] $categoryNodeStorageTransferTreesIndexedByStoreAndLocale
     *
     * @return void
     */
    protected function storeData(array $categoryNodeStorageTransferTreesIndexedByStoreAndLocale): void
    {
        foreach ($categoryNodeStorageTransferTreesIndexedByStoreAndLocale as $storeName => $categoryNodeStorageTransferTreesIndexedByLocale) {
            foreach ($categoryNodeStorageTransferTreesIndexedByLocale as $localeName => $categoryNodeStorageTransferTrees) {
                $this->storeCategoryNodeStorageTransferTreesForStoreAndLocale(
                    $categoryNodeStorageTransferTrees,
                    $storeName,
                    $localeName
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransferTrees
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function storeCategoryNodeStorageTransferTreesForStoreAndLocale(
        array $categoryNodeStorageTransferTrees,
        string $storeName,
        string $localeName
    ): void {
        foreach ($categoryNodeStorageTransferTrees as $categoryNodeStorageTransfer) {
            $this->storeDataSet($categoryNodeStorageTransfer, $storeName, $localeName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function storeDataSet(
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer,
        string $storeName,
        string $localeName
    ): void {
        $this->categoryStorageEntityManager->saveCategoryNodeStorageForStoreAndLocale(
            $categoryNodeStorageTransfer,
            $storeName,
            $localeName
        );
    }

    /**
     * @param array $nodeTransfers
     * @param array $categoryNodeIds
     *
     * @return void
     */
    protected function deleteMissingCategoryNodeStorage(array $nodeTransfers, array $categoryNodeIds): void
    {
        $existingCategoryNodeIds = $this->getCategoryNodeIdsFromNodeTransfers($nodeTransfers);
        $categoryNodeIdsToDelete = array_diff($categoryNodeIds, $existingCategoryNodeIds);

        $this->categoryStorageEntityManager->deleteCategoryNodeStorageByCategoryNodeIds($categoryNodeIdsToDelete);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     *
     * @return int[]
     */
    protected function getCategoryNodeIdsFromNodeTransfers(array $nodeTransfers): array
    {
        return array_map(function (NodeTransfer $nodeTransfer): int {
            return $nodeTransfer->getIdCategoryNode();
        }, $nodeTransfers);
    }
}
