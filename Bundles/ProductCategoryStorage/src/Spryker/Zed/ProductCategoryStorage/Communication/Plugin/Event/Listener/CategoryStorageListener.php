<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductCategoryStorage\Communication\ProductCategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategoryStorage\ProductCategoryStorageConfig getConfig()
 */
class CategoryStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
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

        $categoryIds = $this->getCategoryIds($eventTransfers, $eventName);
        if (empty($categoryIds)) {
            return;
        }

        $relatedCategoryIds = $this->getFacade()
            ->getRelatedCategoryIds($categoryIds);

        $productAbstractIds = $this->getQueryContainer()
            ->queryProductAbstractIdsByCategoryIds($relatedCategoryIds)
            ->find()
            ->getData();

        $this->getFacade()->publish($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return int[]
     */
    protected function getCategoryIds(array $eventTransfers, $eventName): array
    {
        if ($eventName === CategoryEvents::ENTITY_SPY_CATEGORY_DELETE) {
            return $this->getFactory()
                ->getEventBehaviorFacade()
                ->getEventTransferIds($eventTransfers);
        }

        return $this->getValidCategoryIds($eventTransfers);
    }

    /**
     * @param array $eventTransfers
     *
     * @return int[]
     */
    protected function getValidCategoryIds(array $eventTransfers)
    {
        $validEventTransfers = $this->getFactory()->getEventBehaviorFacade()->getEventTransfersByModifiedColumns(
            $eventTransfers,
            [
                SpyCategoryTableMap::COL_IS_ACTIVE,
                SpyCategoryTableMap::COL_CATEGORY_KEY,
            ]
        );

        return $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($validEventTransfers);
    }
}
