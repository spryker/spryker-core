<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Deleter;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodeExtractorInterface;
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
     * @var \Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodeExtractorInterface
     */
    protected $categoryNodeExtractor;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface $categoryPageSearchEntityManager
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryPageSearch\Business\Extractor\CategoryNodeExtractorInterface $categoryNodeExtractor
     */
    public function __construct(
        CategoryPageSearchEntityManagerInterface $categoryPageSearchEntityManager,
        CategoryPageSearchToCategoryFacadeInterface $categoryFacade,
        CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodeExtractorInterface $categoryNodeExtractor
    ) {
        $this->categoryPageSearchEntityManager = $categoryPageSearchEntityManager;
        $this->categoryFacade = $categoryFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodeExtractor = $categoryNodeExtractor;
    }

    /**
     * @param array<int> $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollection(array $categoryNodeIds): void
    {
        $this->categoryPageSearchEntityManager->deleteCategoryNodePageSearchByCategoryNodeIds($categoryNodeIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
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
    public function deleteCategoryNodeStorageCollectionByCategoryNodeCriteria(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): void
    {
        $nodeCollectionTransfer = $this->categoryFacade->getCategoryNodes($categoryNodeCriteriaTransfer);
        $categoryNodeIds = $this->categoryNodeExtractor->extractCategoryNodeIdsFromNodeCollection($nodeCollectionTransfer);

        $this->deleteCategoryNodePageSearchCollection($categoryNodeIds);
    }
}
