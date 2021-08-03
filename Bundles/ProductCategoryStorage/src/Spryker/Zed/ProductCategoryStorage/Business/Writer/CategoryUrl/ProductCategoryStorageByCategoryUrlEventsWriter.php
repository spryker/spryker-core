<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryUrl;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface;

class ProductCategoryStorageByCategoryUrlEventsWriter implements ProductCategoryStorageByCategoryUrlEventsWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface
     */
    protected $productCategoryStorageWriter;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryInterface $categoryFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface $productCategoryStorageWriter
     */
    public function __construct(
        ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductCategoryStorageToCategoryInterface $categoryFacade,
        ProductCategoryStorageWriterInterface $productCategoryStorageWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryFacade = $categoryFacade;
        $this->productCategoryStorageWriter = $productCategoryStorageWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByCategoryUrlEvents(array $eventEntityTransfers): void
    {
        $categoryNodeIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE
        );

        if ($categoryNodeIds === []) {
            return;
        }

        $categoryNodeIds = array_map('intval', $categoryNodeIds);

        $categoryNodeCollection = $this->categoryFacade->getCategoryNodes(
            (new CategoryNodeCriteriaTransfer())->setCategoryNodeIds($categoryNodeIds)
        );

        $categoryIds = $this->extractCategoryIdsFromNodeCollection($categoryNodeCollection);

        $this->productCategoryStorageWriter->writeCollectionByRelatedCategories($categoryIds, true);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByCategoryUrlAndResourceCategorynodeEvents(array $eventEntityTransfers): void
    {
        $modifiedColumnsEventTransfer = $this->eventBehaviorFacade->getEventTransfersByModifiedColumns(
            $eventEntityTransfers,
            [
                SpyUrlTableMap::COL_URL,
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE,
            ]
        );

        $this->writeCollectionByCategoryUrlEvents($modifiedColumnsEventTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return int[]
     */
    protected function extractCategoryIdsFromNodeCollection(NodeCollectionTransfer $nodeCollectionTransfer): array
    {
        $categoryIds = [];
        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $categoryIds[] = $nodeTransfer->getFkCategoryOrFail();
        }

        return array_unique($categoryIds);
    }
}
