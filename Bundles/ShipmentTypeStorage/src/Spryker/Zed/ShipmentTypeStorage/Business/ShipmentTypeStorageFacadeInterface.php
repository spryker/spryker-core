<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ShipmentTypeStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes shipment type data by `SpyShipmentType` entity events.
     * - Extracts shipment type IDs from the `$eventEntityTransfers` created by shipment type entity events and shipment type publish event.
     * - Executes stack of {@link \Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface} plugins.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentTypeEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes shipment type data by `SpyShipmentTypeStore` entity events.
     * - Extracts shipment type IDs from the `$eventEntityTransfers` created by shipment type store entity events.
     * - Executes stack of {@link \Spryker\Zed\ShipmentTypeStorageExtension\Dependency\Plugin\ShipmentTypeStorageExpanderPluginInterface} plugins.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentTypeStoreEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Retrieves a collection of shipment type storage transfers according to provided offset, limit and IDs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $shipmentTypeIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getShipmentTypeStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $shipmentTypeIds = []): array;
}
