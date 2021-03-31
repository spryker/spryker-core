<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Writer;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface;
use Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeStorageExtractorInterface;
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
     * @var \Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeStorageExtractorInterface
     */
    protected $categoryNodeStorageExtractor;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface $categoryStorageEntityManager
     * @param \Spryker\Zed\CategoryStorage\Business\TreeBuilder\CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface $categoryNodeStorageDeleter
     * @param \Spryker\Zed\CategoryStorage\Business\Extractor\CategoryNodeStorageExtractorInterface $categoryNodeStorageExtractor
     */
    public function __construct(
        CategoryStorageEntityManagerInterface $categoryStorageEntityManager,
        CategoryStorageNodeTreeBuilderInterface $categoryStorageNodeTreeBuilder,
        CategoryStorageToCategoryFacadeInterface $categoryFacade,
        CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodeStorageDeleterInterface $categoryNodeStorageDeleter,
        CategoryNodeStorageExtractorInterface $categoryNodeStorageExtractor
    ) {
        $this->categoryStorageEntityManager = $categoryStorageEntityManager;
        $this->categoryStorageNodeTreeBuilder = $categoryStorageNodeTreeBuilder;
        $this->categoryFacade = $categoryFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodeStorageDeleter = $categoryNodeStorageDeleter;
        $this->categoryNodeStorageExtractor = $categoryNodeStorageExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $this->writeCategoryNodeStorageCollectionByForeignKeyContainingEvents(
            $eventEntityTransfers,
            SpyCategoryStoreTableMap::COL_FK_CATEGORY
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void
    {
        $this->writeCategoryNodeStorageCollectionByForeignKeyContainingEvents(
            $eventEntityTransfers,
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryStorePublishEvents(array $eventEntityTransfers): void
    {
        $this->writeCategoryNodeStorageCollectionByIdCategoryContainingEvents($eventEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryEvents(array $eventEntityTransfers): void
    {
        $this->writeCategoryNodeStorageCollectionByIdCategoryContainingEvents($eventEntityTransfers);
    }

    /**
     * @param int[] $categoryNodeIds
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
                $nodeTransfers
            );

        $this->storeData($categoryNodeStorageTransferTreesIndexedByLocaleAndStore);
        $this->categoryNodeStorageDeleter->deleteMissingCategoryNodeStorage($categoryNodeStorageTransferTreesIndexedByLocaleAndStore, $categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void
    {
        $categoryTemplateIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryTemplateIds($categoryTemplateIds)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByParentCategoryEvents(array $eventEntityTransfers): void
    {
        $parentCategoryNodeIds = $this->eventBehaviorFacade
            ->getEventTransferForeignKeys(
                $eventEntityTransfers,
                SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE
            );

        $originalParentCategoryNodeIds = $this->eventBehaviorFacade
            ->getEventTransfersOriginalValues(
                $eventEntityTransfers,
                SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE
            );

        $categoryNodeIds = array_unique(array_merge($parentCategoryNodeIds, $originalParentCategoryNodeIds));

        $this->writeCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryNodeEvents(array $eventEntityTransfers): void
    {
        $categoryNodeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCategoryNodeStorageCollection($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    protected function writeCategoryNodeStorageCollectionByIdCategoryContainingEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $foreignKeyName
     *
     * @return void
     */
    protected function writeCategoryNodeStorageCollectionByForeignKeyContainingEvents(array $eventEntityTransfers, string $foreignKeyName): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, $foreignKeyName);

        $this->writeCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return void
     */
    protected function writeCategoryNodeStorageCollectionByCategoryNodeCriteria(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): void
    {
        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodes($categoryNodeCriteriaTransfer);
        $categoryNodeIds = $this->categoryNodeStorageExtractor
            ->extractCategoryNodeIdsFromNodeCollection($nodeCollectionTransfer);

        $this->writeCategoryNodeStorageCollection($categoryNodeIds);
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
}
