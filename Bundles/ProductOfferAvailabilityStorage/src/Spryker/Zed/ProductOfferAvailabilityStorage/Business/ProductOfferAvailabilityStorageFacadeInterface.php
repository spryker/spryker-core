<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business;

interface ProductOfferAvailabilityStorageFacadeInterface
{
    /**
     * Specification:
     * - Extracts oms product reservation ids from event transfers.
     * - Gets availability for product offer oms product reservation ids.
     * - Saves product offer availability to storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferAvailabilityStorageCollectionByOmsProductReservationKeyEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Extracts product offer stock ids from event transfers.
     * - Gets product offer availability product offer stock ids.
     * - Saves product offer availability to storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferAvailabilityStorageCollectionByProductOfferStockKeyEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Extracts product offer ids from event transfers.
     * - Gets product offer availability by product offer ids.
     * - Saves product offer availability to storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferAvailabilityStorageCollectionByProductOfferKeyEvents(array $eventTransfers): void;
}
