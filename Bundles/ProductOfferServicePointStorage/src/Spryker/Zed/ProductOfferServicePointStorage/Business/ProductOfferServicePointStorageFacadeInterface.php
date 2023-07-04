<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductOfferServicePointStorageFacadeInterface
{
    /**
     * Specification:
     * - Extracts product offer IDs from the `$eventEntityTransfers` created by product offer service entity events.
     * - Gets product offer services data.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferServiceEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts product offer IDs from the `$eventEntityTransfers` created by product offer service publish events.
     * - Gets product offer services data.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferServicePublishEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts product offer IDs from the `$eventEntityTransfers` created by product offer entity events.
     * - Gets product offer services data.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts product offer IDs from the `$eventEntityTransfers` created by product offer store entity events.
     * - Gets product offer services data.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByProductOfferStoreEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts service IDs from the `$eventEntityTransfers` created by service entity events.
     * - Gets product offer services data.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByServiceEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts service point IDs from the `$eventEntityTransfers` created by service point entity events.
     * - Gets product offer services data.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByServicePointEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts service point IDs from the `$eventEntityTransfers` created by service point store entity events.
     * - Gets product offer services data.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollectionByServicePointStoreEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Retrieves a collection of product offer service storage transfers according to provided `FilterTransfer` and product offer service IDs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $productOfferServiceIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferServiceStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $productOfferServiceIds = []): array;
}
