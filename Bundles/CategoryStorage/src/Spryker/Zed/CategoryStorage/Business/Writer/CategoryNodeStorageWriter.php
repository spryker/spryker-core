<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Writer;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface;
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
     * @var \Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface
     */
    protected $categoryNodeStorageDeleter;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface $categoryStorageEntityManager
     * @param \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface $categoryNodeStorageDeleter
     */
    public function __construct(
        CategoryStorageEntityManagerInterface $categoryStorageEntityManager,
        CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder,
        CategoryStorageToCategoryFacadeInterface $categoryFacade,
        CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodeStorageDeleterInterface $categoryNodeStorageDeleter
    ) {
        $this->categoryStorageEntityManager = $categoryStorageEntityManager;
        $this->categoryStorageNodeTreeBuilder = $categoryStorageNodeTreeBuilder;
        $this->categoryFacade = $categoryFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodeStorageDeleter = $categoryNodeStorageDeleter;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, SpyCategoryStoreTableMap::COL_FK_CATEGORY);
        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodesByCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );

        $categoryNodeIds = $this->extractCategoryNodeIdsFromNodeCollection($nodeCollectionTransfer);

        $this->writeCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryStorePublishEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);
        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodesByCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );

        $categoryNodeIds = $this->extractCategoryNodeIdsFromNodeCollection($nodeCollectionTransfer);

        $this->writeCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollection(array $categoryNodeIds): void
    {
        $nodeTransfers = $this->categoryFacade->getCategoryNodesWithRelativeNodesByCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryNodeIds($categoryNodeIds)
        );

        $categoryNodeStorageTransferTreesIndexedByLocaleAndStore = $this->categoryStorageNodeTreeBuilder
            ->buildCategoryNodeStorageTransferTreesForLocaleAndStore(
                $categoryNodeIds,
                $nodeTransfers
            );

        $this->storeData($categoryNodeStorageTransferTreesIndexedByLocaleAndStore);
        $this->categoryNodeStorageDeleter->deleteMissingCategoryNodeStorage($categoryNodeStorageTransferTreesIndexedByLocaleAndStore, $categoryNodeIds);
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
            $this->categoryStorageEntityManager->saveCategoryNodeStorageForStoreAndLocale(
                $categoryNodeStorageTransfer,
                $storeName,
                $localeName
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return int[]
     */
    protected function extractCategoryNodeIdsFromNodeCollection(NodeCollectionTransfer $nodeCollectionTransfer): array
    {
        $categoryNodeIds = [];

        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $categoryNodeIds[] = $nodeTransfer->getIdCategoryNode();
        }

        return $categoryNodeIds;
    }
}
