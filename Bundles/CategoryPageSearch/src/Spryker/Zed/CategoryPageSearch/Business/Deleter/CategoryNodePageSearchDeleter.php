<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Deleter;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodePageSearchExtractorInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface;

class CategoryNodePageSearchDeleter implements CategoryNodePageSearchDeleterInterface
{
    /**
     * @var \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface
     */
    protected $categoryPageSearchEntityManager;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodePageSearchExtractorInterface
     */
    protected $categoryNodePageSearchExtractor;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface $categoryPageSearchEntityManager
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodePageSearchExtractorInterface $categoryNodePageSearchExtractor
     */
    public function __construct(
        CategoryPageSearchEntityManagerInterface $categoryPageSearchEntityManager,
        CategoryPageSearchToCategoryFacadeInterface $categoryFacade,
        CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodePageSearchExtractorInterface $categoryNodePageSearchExtractor
    ) {
        $this->categoryPageSearchEntityManager = $categoryPageSearchEntityManager;
        $this->categoryFacade = $categoryFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodePageSearchExtractor = $categoryNodePageSearchExtractor;
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollection(array $categoryNodeIds): void
    {
        $this->categoryPageSearchEntityManager->deleteCategoryNodePageSearchByCategoryNodeIds($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY
        );

        $this->deleteCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->deleteCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryTemplateEvents(array $eventEntityTransfers): void
    {
        $categoryTemplateIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->deleteCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryTemplateIds($categoryTemplateIds)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryNodeEvents(array $eventEntityTransfers): void
    {
        $categoryNodeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->deleteCategoryNodePageSearchCollection($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return void
     */
    protected function deleteCategoryNodeStorageCollectionByCategoryNodeCriteria(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): void
    {
        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodes($categoryNodeCriteriaTransfer);
        $categoryNodeIds = $this->categoryNodePageSearchExtractor
            ->extractCategoryNodeIdsFromNodeCollection($nodeCollectionTransfer);

        $this->deleteCategoryNodePageSearchCollection($categoryNodeIds);
    }
}
