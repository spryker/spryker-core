<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Publisher\ProductOfferAvailability;

use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Business\ProductOfferAvailabilityStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferAvailabilityStorage\Communication\ProductOfferAvailabilityStorageCommunicationFactory getFactory()
 */
class ProductOfferAvailabilityProductOfferStoreStoragePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extracts product offer IDs from the `$eventEntityTransfers` created by product offer store entity events.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByProductOfferStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            ProductOfferAvailabilityStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_DELETE,
            ProductOfferAvailabilityStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_UPDATE,
            ProductOfferAvailabilityStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE,
        ];
    }
}
