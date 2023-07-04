<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Business;

interface ServicePointStorageFacadeInterface
{
    /**
     * Specification:
     * - Extracts service point IDs from the `$eventEntityTransfers` created by service point entity events.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServicePointEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts service point IDs from the `$eventEntityTransfers` created by service point address entity events.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServicePointAddressEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts service point IDs from the `$eventEntityTransfers` created by service point store entity events.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServicePointStoreEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Extracts service point IDs from the `$eventEntityTransfers` created by service entity events.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServiceEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Retrieves a collection of service point storage transfers according to provided offset, limit and IDs.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param list<int> $servicePointIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getServicePointStorageSynchronizationDataTransfers(int $offset, int $limit, array $servicePointIds = []): array;
}
