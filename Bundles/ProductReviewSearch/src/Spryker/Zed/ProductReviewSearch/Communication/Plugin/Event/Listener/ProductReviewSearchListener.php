<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReviewSearch\Communication\ProductReviewSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductReviewSearch\ProductReviewSearchConfig getConfig()
 */
class ProductReviewSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
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
        $hydrateEventsResponseTransfer = $this->getFactory()->getEventBehaviorFacade()->hydrateEventDataTransfer(
            (new HydrateEventsRequestTransfer())
                ->setEventEntities(new ArrayObject($eventEntityTransfers))
                ->setForeignKeyName(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT),
        );

        $this->getFacade()->publish(array_keys($hydrateEventsResponseTransfer->getIdTimestampMap()));

        if ($hydrateEventsResponseTransfer->getForeignKeyTimestampMap()) {
            $this->getFactory()->getProductPageSearchFacade()->publishWithTimestamp($hydrateEventsResponseTransfer->getForeignKeyTimestampMap());
        }
    }
}
