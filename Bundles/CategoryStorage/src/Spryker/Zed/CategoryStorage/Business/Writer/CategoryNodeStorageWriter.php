<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Writer;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface;
use Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface;
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
     * @var \Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface
     */
    protected $categoryNodeExtractor;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface $categoryStorageEntityManager
     * @param \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface $categoryNodeStorageDeleter
     * @param \Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeExtractorInterface $categoryNodeExtractor
     */
    public function __construct(
        CategoryStorageEntityManagerInterface $categoryStorageEntityManager,
        CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder,
        CategoryStorageToCategoryFacadeInterface $categoryFacade,
        CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodeStorageDeleterInterface $categoryNodeStorageDeleter,
        CategoryNodeExtractorInterface $categoryNodeExtractor
    ) {
        $this->categoryStorageEntityManager = $categoryStorageEntityManager;
        $this->categoryStorageNodeTreeBuilder = $categoryStorageNodeTreeBuilder;
        $this->categoryFacade = $categoryFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodeStorageDeleter = $categoryNodeStorageDeleter;
        $this->categoryNodeExtractor = $categoryNodeExtractor;
    }

    /**
     * @param array<int> $categoryNodeIds
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollection(array $categoryNodeIds): void
    {
        $nodeTransfers = $this->categoryFacade
            ->getCategoryNodesWithRelativeNodes((new CategoryNodeCriteriaTransfer())->setCategoryNodeIds($categoryNodeIds))
            ->getNodes()
            ->getArrayCopy();

        $categoryNodeStorageTransferTreesIndexedByLocaleAndStore = $this->categoryStorageNodeTreeBuilder
            ->buildCategoryNodeStorageTransferTreesForLocaleAndStore(
                $categoryNodeIds,
                $nodeTransfers,
            );

        $this->storeData($categoryNodeStorageTransferTreesIndexedByLocaleAndStore);
        $this->categoryNodeStorageDeleter->deleteMissingCategoryNodeStorage($categoryNodeStorageTransferTreesIndexedByLocaleAndStore, $categoryNodeIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByParentCategoryEvents(array $eventEntityTransfers): void
    {
        $parentCategoryNodeIds = $this->eventBehaviorFacade
            ->getEventTransferForeignKeys(
                $eventEntityTransfers,
                SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE,
            );

        $originalParentCategoryNodeIds = $this->eventBehaviorFacade
            ->getEventTransfersOriginalValues(
                $eventEntityTransfers,
                SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE,
            );

        $categoryNodeIds = array_unique(array_merge($parentCategoryNodeIds, $originalParentCategoryNodeIds));

        $this->writeCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryNodeEvents(array $eventEntityTransfers): void
    {
        $categoryNodeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryNodeCriteria(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): void
    {
        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodes($categoryNodeCriteriaTransfer);
        $categoryNodeIds = $this->categoryNodeExtractor->extractCategoryNodeIdsFromNodeCollection($nodeCollectionTransfer);

        $this->writeCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer[][]> $categoryNodeStorageTransferTreesIndexedByStoreAndLocale
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
                    $localeName,
                );
            }
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer> $categoryNodeStorageTransferTrees
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
                $localeName,
            );
        }
    }
}
