<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductOfferShipmentTypeStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes product offer shipment type data by `SpyProductOfferShipmentType` entity events and product offer shipment type publish event.
     * - Uses product offer IDs provided in `EventEntityTransfers.foreignKeys` to publish storage data.
     * - If product offer IDs are not provided, uses `idProductOfferShipmenType` provided in `EventEntityTransfers.id` to publish storage data.
     * - Executes {@link \Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface} plugin stack.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferShipmentTypeEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes product offer shipment type data by `SpyProductOffer` entity events and product offer publish event.
     * - Extracts product offer IDs from the `$eventEntityTransfers` created by product offer entity events and product offer publish event.
     * - Executes {@link \Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface} plugin stack.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes product offer shipment type data by `SpyProductOfferStore` entity events.
     * - Extracts product offer IDs from the `$eventEntityTransfers` created by product offer store entity events.
     * - Executes {@link \Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface} plugin stack.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferStoreEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes product offer shipment type data by `SpyShipmentType` entity events and shipment type publish event.
     * - Extracts shipment type IDs from the `$eventEntityTransfers` created by shipment type entity events and shipment type publish event.
     * - Executes {@link \Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface} plugin stack.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByShipmentTypeEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes product offer shipment type data by `SpyShipmentTypeStore` entity events.
     * - Extracts shipment type IDs from the `$eventEntityTransfers` created by shipment type store entity events.
     * - Executes {@link \Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface} plugin stack.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByShipmentTypeStoreEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     *  - Retrieves product offer shipment type storage data according to provided `Filter.offset`, `Filter.limit` and product offer IDs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $productOfferIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
        FilterTransfer $filterTransfer,
        array $productOfferIds = []
    ): array;
}
