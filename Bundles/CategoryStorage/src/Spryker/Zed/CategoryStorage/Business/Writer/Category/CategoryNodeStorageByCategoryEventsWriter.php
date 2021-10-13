<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Writer\Category;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface;

class CategoryNodeStorageByCategoryEventsWriter implements CategoryNodeStorageByCategoryEventsWriterInterface
{
    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface
     */
    protected $categoryNodeStorageWriter;

    /**
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface $categoryNodeStorageWriter
     */
    public function __construct(
        CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodeStorageWriterInterface $categoryNodeStorageWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodeStorageWriter = $categoryNodeStorageWriter;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCategoryNodeStorageCollectionByCategoryEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->categoryNodeStorageWriter->writeCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );
    }
}
