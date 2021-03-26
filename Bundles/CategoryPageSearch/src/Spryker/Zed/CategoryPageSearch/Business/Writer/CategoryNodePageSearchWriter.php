<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Writer;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodePageSearchTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleterInterface;
use Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodePageSearchExtractorInterface;
use Spryker\Zed\CategoryPageSearch\Business\Mapper\CategoryNodePageSearchMapperInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface;

class CategoryNodePageSearchWriter implements CategoryNodePageSearchWriterInterface
{
    /**
     * @var \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface
     */
    protected $categoryPageSearchEntityManager;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Business\Mapper\CategoryNodePageSearchMapperInterface
     */
    protected $categoryNodePageSearchMapper;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleterInterface
     */
    protected $categoryNodePageSearchDeleter;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodePageSearchExtractorInterface
     */
    protected $categoryNodePageSearchExtractor;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface $categoryPageSearchEntityManager
     * @param \Spryker\Zed\CategoryPageSearch\Business\Mapper\CategoryNodePageSearchMapperInterface $categoryNodePageSearchMapper
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleterInterface $categoryNodePageSearchDeleter
     * @param \Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodePageSearchExtractorInterface $categoryNodePageSearchExtractor
     */
    public function __construct(
        CategoryPageSearchEntityManagerInterface $categoryPageSearchEntityManager,
        CategoryNodePageSearchMapperInterface $categoryNodePageSearchMapper,
        CategoryPageSearchToCategoryFacadeInterface $categoryFacade,
        CategoryPageSearchToStoreFacadeInterface $storeFacade,
        CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodePageSearchDeleterInterface $categoryNodePageSearchDeleter,
        CategoryNodePageSearchExtractorInterface $categoryNodePageSearchExtractor
    ) {
        $this->categoryPageSearchEntityManager = $categoryPageSearchEntityManager;
        $this->categoryNodePageSearchMapper = $categoryNodePageSearchMapper;
        $this->categoryFacade = $categoryFacade;
        $this->storeFacade = $storeFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodePageSearchDeleter = $categoryNodePageSearchDeleter;
        $this->categoryNodePageSearchExtractor = $categoryNodePageSearchExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $this->writeCategoryNodePageSearchCollectionByForeignKey(
            $eventEntityTransfers,
            SpyCategoryStoreTableMap::COL_FK_CATEGORY
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryStorePublishEvents(array $eventEntityTransfers): void
    {
        $this->writeCategoryNodePageSearchCollectionByCategoryIds($eventEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void
    {
        $this->writeCategoryNodePageSearchCollectionByForeignKey(
            $eventEntityTransfers,
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryEvents(array $eventEntityTransfers): void
    {
        $this->writeCategoryNodePageSearchCollectionByCategoryIds($eventEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void
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
    public function writeCategoryNodePageSearchCollectionByCategoryNodeEvents(array $eventEntityTransfers): void
    {
        $categoryNodeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCategoryNodePageSearchCollection($categoryNodeIds);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollection(array $categoryNodeIds): void
    {
        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->setCategoryNodeIds($categoryNodeIds)
            ->setIsActive(true)
            ->setWithRelations(true);

        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodes($categoryNodeCriteriaTransfer);

        $this->storeData($nodeCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return void
     */
    protected function storeData(NodeCollectionTransfer $nodeCollectionTransfer): void
    {
        $localeNameMapByStoreName = $this->getLocaleNameMapByStoreName();
        foreach ($localeNameMapByStoreName as $storeName => $localeNames) {
            foreach ($localeNames as $localeName) {
                $this->storeDataForStoreAndLocale($nodeCollectionTransfer, $storeName, $localeName);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfers
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function storeDataForStoreAndLocale(
        NodeCollectionTransfer $nodeCollectionTransfers,
        string $storeName,
        string $localeName
    ): void {
        foreach ($nodeCollectionTransfers->getNodes() as $nodeTransfer) {
            if (!$this->isCategoryHasStoreRelation($nodeTransfer->getCategoryOrFail(), $storeName)) {
                $this->categoryPageSearchEntityManager->deleteCategoryNodePageSearchByIdCategoryNodeForLocaleAndStore(
                    $nodeTransfer->getIdCategoryNodeOrFail(),
                    $localeName,
                    $storeName
                );

                continue;
            }

            $this->storeDataSet($nodeTransfer, $storeName, $localeName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function storeDataSet(
        NodeTransfer $nodeTransfer,
        string $storeName,
        string $localeName
    ): void {
        $categoryNodePageSearchTransfer = $this->categoryNodePageSearchMapper->mapNodeTransferToCategoryNodePageSearchTransferForStoreAndLocale(
            $nodeTransfer,
            new CategoryNodePageSearchTransfer(),
            $storeName,
            $localeName
        );

        $this->categoryPageSearchEntityManager->saveCategoryNodePageSearch($categoryNodePageSearchTransfer);
    }

    /**
     * @return string[][]
     */
    protected function getLocaleNameMapByStoreName(): array
    {
        $localeNameMapByStoreName = [];
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $localeNameMapByStoreName[$storeTransfer->getName()] = $storeTransfer->getAvailableLocaleIsoCodes();
        }

        return $localeNameMapByStoreName;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $storeName
     *
     * @return bool
     */
    protected function isCategoryHasStoreRelation(CategoryTransfer $categoryTransfer, string $storeName): bool
    {
        foreach ($categoryTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
            if ($storeTransfer->getName() === $storeName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    protected function writeCategoryNodePageSearchCollectionByCategoryIds(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $foreignKey
     *
     * @return void
     */
    protected function writeCategoryNodePageSearchCollectionByForeignKey(array $eventEntityTransfers, string $foreignKey): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, $foreignKey);

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
        $categoryNodeIds = $this->categoryNodePageSearchExtractor
            ->extractCategoryNodeIdsFromNodeCollection($nodeCollectionTransfer);

        $this->writeCategoryNodePageSearchCollection($categoryNodeIds);
    }
}
