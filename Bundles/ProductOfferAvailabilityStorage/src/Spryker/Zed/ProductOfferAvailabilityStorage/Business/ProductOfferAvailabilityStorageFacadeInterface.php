<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business;

interface ProductOfferAvailabilityStorageFacadeInterface
{
    /**
     * Specification:
     * - Extracts oms product offer reservation ids from event transfers.
     * - Gets availability for product offer oms product reservation ids.
     * - Saves product offer availability to storage.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByOmsProductOfferReservationIdEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Extracts product offer stock ids from event transfers.
     * - Gets product offer availability product offer stock ids.
     * - Saves product offer availability to storage.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferStockIdEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Extracts product offer IDs from the `$eventEntityTransfers` created by product offer store entity events.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Extracts product offer ids from event transfers.
     * - Gets product offer availability by product offer ids.
     * - Saves product offer availability to storage.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferIdEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Publishes product offer availability data by `SpyStock` entity events.
     * - Extracts stock IDs from the event transfers created by stock entity events.
     * - Finds product offer IDs by stock IDs.
     * - Calculates product offer availability.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByStockEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Publishes product offer availability data by `SpyStockStore` entity events.
     * - Extracts stock IDs from the event transfers created by stock store entity events.
     * - Finds product offer IDs by stock IDs.
     * - Calculates product offer availability.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByStockStoreEvents(array $eventTransfers): void;
}
