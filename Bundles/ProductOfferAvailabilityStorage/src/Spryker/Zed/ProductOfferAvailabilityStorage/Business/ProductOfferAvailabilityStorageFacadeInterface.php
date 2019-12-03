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
     * - Writes product offer availability data to storage by provided oms product reservation events.
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
     * - Writes product offer availability data to storage by provided offer stock events.
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
     * - Writes product offer availability data to storage by provided offer events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferAvailabilityStorageCollectionByProductOfferKeyEvents(array $eventTransfers): void;
}
