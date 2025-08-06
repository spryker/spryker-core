<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractPublishListener}
 *   and {@link Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractUnpublishListener} instead.
 *
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class ProductPageProductAbstractListener extends AbstractProductPageSearchListener implements EventBulkHandlerInterface
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
        $hydrateEventsResponseTransfer = $this->hydrateEventDataTransfer(
            (new HydrateEventsRequestTransfer())
                ->setEventEntities(new ArrayObject($eventEntityTransfers)),
        );

        if (
            $eventName === ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_DELETE ||
            $eventName === ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH
        ) {
            $this->unpublish($hydrateEventsResponseTransfer->getIdTimestampMap());
        } else {
            $this->publish($hydrateEventsResponseTransfer->getIdTimestampMap());
        }
    }
}
