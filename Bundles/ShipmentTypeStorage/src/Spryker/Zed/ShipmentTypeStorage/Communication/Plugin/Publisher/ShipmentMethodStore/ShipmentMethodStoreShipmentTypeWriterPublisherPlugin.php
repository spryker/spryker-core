<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Communication\Plugin\Publisher\ShipmentMethodStore;

use Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeStorage\Business\ShipmentTypeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentTypeStorage\Communication\ShipmentTypeStorageCommunicationFactory getFactory()
 */
class ShipmentMethodStoreShipmentTypeWriterPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes shipment type data by `SpyShipmentMethodStore` entity events.
     * - Extracts shipment method IDs from the `$eventEntityTransfers` created by shipment method store entity events.
     * - Finds shipment type IDs by shipment method IDs.
     * - Expands shipment type storage data with related shipment method IDs.
     * - Executes stack of {@link \Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface} plugins.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeShipmentTypeStorageCollectionByShipmentMethodStoreEvents($eventEntityTransfers);
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
            ShipmentTypeStorageConfig::ENTITY_SPY_SHIPMENT_METHOD_STORE_CREATE,
            ShipmentTypeStorageConfig::ENTITY_SPY_SHIPMENT_METHOD_STORE_DELETE,
        ];
    }
}
