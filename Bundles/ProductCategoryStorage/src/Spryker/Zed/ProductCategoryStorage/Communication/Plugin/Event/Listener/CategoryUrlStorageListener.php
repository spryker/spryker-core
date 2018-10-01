<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryStorage\Communication\ProductCategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacadeInterface getFacade()
 */
class CategoryUrlStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
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

        if ($eventName === UrlEvents::ENTITY_SPY_URL_DELETE) {
            $categoryNodeIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE);
        } else {
            $categoryNodeIds = $this->getValidCategoryNodeIds($eventTransfers);
        }

        if (empty($categoryNodeIds)) {
            return;
        }

        $categoryIds = $this->getQueryContainer()->queryCategoryIdsByNodeIds($categoryNodeIds)->find()->getData();
        $relatedCategoryIds = $this->getFacade()->getRelatedCategoryIds($categoryIds);
        $productAbstractIds = $this->getQueryContainer()->queryProductAbstractIdsByCategoryIds($relatedCategoryIds)->find()->getData();

        $this->getFacade()->publish($productAbstractIds);
    }

    /**
     * @param array $eventTransfers
     *
     * @return array
     */
    protected function getValidCategoryNodeIds(array $eventTransfers)
    {
        $validEventTransfers = $this->getFactory()->getEventBehaviorFacade()->getEventTransfersByModifiedColumns(
            $eventTransfers,
            [
                SpyUrlTableMap::COL_URL,
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE,
            ]
        );

        return $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($validEventTransfers, SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE);
    }
}
