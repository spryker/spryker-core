<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Communication\Plugin\Publisher\ShipmentType;

use Spryker\Shared\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\ProductOfferShipmentTypeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Communication\ProductOfferShipmentTypeStorageCommunicationFactory getFactory()
 */
class ShipmentTypeProductOfferShipmentTypeWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes product offer shipment type data by `SpyShipmentType` entity events and shipment type publish event.
     * - Extracts shipment type IDs from the `$eventEntityTransfers` created by shipment type entity events and shipment type publish event.
     * - Executes {@link \Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface} plugin stack.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByShipmentTypeEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return list<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            ProductOfferShipmentTypeStorageConfig::SHIPMENT_TYPE_PUBLISH,
            ProductOfferShipmentTypeStorageConfig::ENTITY_SPY_SHIPMENT_TYPE_UPDATE,
        ];
    }
}
