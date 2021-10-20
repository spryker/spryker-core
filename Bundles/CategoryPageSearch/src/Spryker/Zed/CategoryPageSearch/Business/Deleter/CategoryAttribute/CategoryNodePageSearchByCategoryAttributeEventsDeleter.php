<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryAttribute;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleterInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface;

class CategoryNodePageSearchByCategoryAttributeEventsDeleter implements CategoryNodePageSearchByCategoryAttributeEventsDeleterInterface
{
    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleterInterface
     */
    protected $categoryNodePageSearchDeleter;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\CategoryPageSearch\Business\Deleter\CategoryNodePageSearchDeleterInterface $categoryNodePageSearchDeleter
     */
    public function __construct(
        CategoryPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        CategoryNodePageSearchDeleterInterface $categoryNodePageSearchDeleter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->categoryNodePageSearchDeleter = $categoryNodePageSearchDeleter;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollectionByCategoryAttributeEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
        );

        $this->categoryNodePageSearchDeleter->deleteCategoryNodeStorageCollectionByCategoryNodeCriteria(
            (new CategoryNodeCriteriaTransfer())->setCategoryIds($categoryIds),
        );
    }
}
