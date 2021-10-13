<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Deleter\CategoryAttribute;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface;

class CategoryNodeStorageByCategoryAttributeEventsDeleter implements CategoryNodeStorageByCategoryAttributeEventsDeleterInterface
{
    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface
     */
    protected $categoryNodeStorageDeleter;

    /**
     * @param \Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryStorage\Business\Deleter\CategoryNodeStorageDeleterInterface $categoryNodeStorageDeleter
     */
    public function __construct(
        CategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodeStorageDeleterInterface $categoryNodeStorageDeleter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodeStorageDeleter = $categoryNodeStorageDeleter;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY
        );

        $this->categoryNodeStorageDeleter->deleteCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds)
        );
    }
}
