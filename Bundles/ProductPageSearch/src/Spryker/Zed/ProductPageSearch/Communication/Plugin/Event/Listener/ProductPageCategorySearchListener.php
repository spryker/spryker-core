<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class ProductPageCategorySearchListener extends AbstractProductPageSearchListener implements EventBulkHandlerInterface
{
    /**
     * @uses \Spryker\Zed\Category\Dependency\CategoryEvents::ENTITY_CATEGORY_PUBLISH
     *
     * @var string
     */
    protected const ENTITY_CATEGORY_PUBLISH = 'Entity.spy_category.publish';

    /**
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        if ($eventName === CategoryEvents::ENTITY_SPY_CATEGORY_DELETE || $eventName === static::ENTITY_CATEGORY_PUBLISH) {
            $categoryIdTimestampMap = $this->hydrateEventDataTransfer(
                (new HydrateEventsRequestTransfer())
                    ->setEventEntities(new ArrayObject($eventEntityTransfers)),
            )->getIdTimestampMap();
        } else {
            $categoryIdTimestampMap = $this->getValidCategoryIds($eventEntityTransfers);
        }

        if (!$categoryIdTimestampMap) {
            return;
        }

        $relatedCategoryIds = $this->getRelatedCategoryIds(array_keys($categoryIdTimestampMap));
        $productAbstractIds = $this->getRepository()->getProductAbstractIdsByCategoryIds($relatedCategoryIds);
        if (!$productAbstractIds) {
            return;
        }

        $this->publish(array_fill_keys($productAbstractIds, min($categoryIdTimestampMap)));
    }

    /**
     * @param array $eventTransfers
     *
     * @return array<int, int>
     */
    protected function getValidCategoryIds(array $eventTransfers)
    {
        $validEventTransfers = $this->getFactory()->getEventBehaviorFacade()->getEventTransfersByModifiedColumns(
            $eventTransfers,
            [
                SpyCategoryTableMap::COL_IS_ACTIVE,
                SpyCategoryTableMap::COL_CATEGORY_KEY,
            ],
        );

        $categoryIdTimestampMap = $this->hydrateEventDataTransfer(
            (new HydrateEventsRequestTransfer())
                ->setEventEntities(new ArrayObject($validEventTransfers)),
        )->getIdTimestampMap();

        return $categoryIdTimestampMap;
    }
}
