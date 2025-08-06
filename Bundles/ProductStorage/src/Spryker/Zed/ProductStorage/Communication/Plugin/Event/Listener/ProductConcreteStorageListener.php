<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteStoragePublishListener}
 *   and {@link \Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener\ProductConcreteStorageUnpublishListener} instead.
 *
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 */
class ProductConcreteStorageListener extends AbstractProductConcreteStorageListener implements EventBulkHandlerInterface
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
            $eventName === ProductEvents::ENTITY_SPY_PRODUCT_DELETE ||
            $eventName === ProductEvents::PRODUCT_CONCRETE_UNPUBLISH
        ) {
            $this->unpublishConcreteProducts($hydrateEventsResponseTransfer->getIdTimestampMap());
        } else {
            $this->publishConcreteProducts($hydrateEventsResponseTransfer->getIdTimestampMap());
        }
    }
}
