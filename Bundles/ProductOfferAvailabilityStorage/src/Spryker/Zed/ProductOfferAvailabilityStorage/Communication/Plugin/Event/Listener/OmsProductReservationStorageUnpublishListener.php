<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Business\ProductOfferAvailabilityStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Communication\ProductOfferAvailabilityStorageCommunicationFactory getFactory()
 */
class OmsProductReservationStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     * - Writes product offer availability data to storage by provided oms product reservation events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $this->getFacade()
            ->writeCollectionByOmsProductOfferReservationIdEvents($eventTransfers);
    }
}
