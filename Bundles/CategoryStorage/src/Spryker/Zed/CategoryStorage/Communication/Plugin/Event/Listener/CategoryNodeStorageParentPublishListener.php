<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated use {@link \Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\ParentWritePublisherPlugin} instead.
 *
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 */
class CategoryNodeStorageParentPublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $parentCategoryNodeIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventEntityTransfers, SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE);

        $originalParentCategoryNodeIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransfersOriginalValues($eventEntityTransfers, SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE);

        $parentCategoryNodeIds = array_unique(array_merge($parentCategoryNodeIds, $originalParentCategoryNodeIds));

        $this->getFacade()->publish($parentCategoryNodeIds);
    }
}
