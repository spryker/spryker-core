<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 */
class CategoryNodeStorageParentPublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();

        $parentCategoryNodeIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE);

        $originalParentCategoryNodeIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransfersOriginalValues($eventTransfers, SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE);

        $parentCategoryNodeIds = array_unique(array_merge($parentCategoryNodeIds, $originalParentCategoryNodeIds));

        $this->getFacade()->publish($parentCategoryNodeIds);
    }
}
