<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryStore;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriterInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface;

class CategoryNodePageSearchByCategoryStoreEventsWriter implements CategoryNodePageSearchByCategoryStoreEventsWriterInterface
{
    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriterInterface
     */
    protected $categoryNodePageSearchWriter;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriterInterface $categoryNodePageSearchWriter
     */
    public function __construct(
        CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodePageSearchWriterInterface $categoryNodePageSearchWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodePageSearchWriter = $categoryNodePageSearchWriter;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyCategoryStoreTableMap::COL_FK_CATEGORY
        );

        $this->categoryNodePageSearchWriter->writeCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodePageSearchCollectionByCategoryStorePublishEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->categoryNodePageSearchWriter->writeCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );
    }
}
