<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 */
class CategoryAttributeCategoryNodeStoragePublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritDoc}
     * - Publishes changes in child and parent category nodes by `CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE` event.
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
        if ($eventName !== CategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE) {
            return;
        }

        $this->preventTransaction();

        $categoryNodeId = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyCategoryAttributeTableMap::COL_FK_CATEGORY)[0];

        $categoryNodeIdsToPublish = array_merge(
            $this->getParentCategoryNodeIds([], $categoryNodeId),
            $this->getRepository()->getCategoryNodeIdsByParentCategoryNodeId($categoryNodeId)
        );

        $this->getFacade()->publish($categoryNodeIdsToPublish);
    }

    /**
     * @param int[] $parentCategoryNodeIds
     * @param int $categoryNodeId
     *
     * @return int[]
     */
    public function getParentCategoryNodeIds(array $parentCategoryNodeIds, int $categoryNodeId): array
    {
        if (!$categoryNodeId) {
            return $parentCategoryNodeIds;
        }

        $parentCategoryNodeId = $this->getRepository()->findParentCategoryNodeIdByCategoryNodeId($categoryNodeId);
        if (!$parentCategoryNodeId) {
            return $parentCategoryNodeIds;
        }

        $parentCategoryNodeIds[] = $parentCategoryNodeId;

        return $this->getParentCategoryNodeIds($parentCategoryNodeIds, $parentCategoryNodeId);
    }
}
