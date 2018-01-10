<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 */
class CategoryNodeCategoryAttributeStorageListener extends AbstractCategoryNodeStorageListener
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $categoryIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyCategoryAttributeTableMap::COL_FK_CATEGORY);
        $categoryNodeIds = $this->getQueryContainer()->queryCategoryNodeIdsByCategoryIds($categoryIds)->find()->getData();

        if ($eventName === CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_DELETE) {
            $this->unpublish($categoryNodeIds);
        } else {
            $this->publish($categoryNodeIds);
        }
    }
}
