<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Communication\Plugin\Publisher\ShipmentMethod;

use Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\ShipmentTypeStorageConfig getConfig()
 * @method \Spryker\Zed\ShipmentTypeStorage\Business\ShipmentTypeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentTypeStorage\Communication\ShipmentTypeStorageCommunicationFactory getFactory()
 */
class ShipmentMethodShipmentTypeWriterPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes shipment type data by `SpyShipmentMethod` entity events.
     * - Extracts shipment type IDs from the `$eventEntityTransfers` created by shipment method entity events.
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
        $this->getFacade()->writeShipmentTypeStorageCollectionByShipmentMethodEvents($eventEntityTransfers);
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
            ShipmentTypeStorageConfig::ENTITY_SPY_SHIPMENT_METHOD_CREATE,
            ShipmentTypeStorageConfig::ENTITY_SPY_SHIPMENT_METHOD_UPDATE,
            ShipmentTypeStorageConfig::ENTITY_SPY_SHIPMENT_METHOD_DELETE,
        ];
    }
}
