<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business\Writer;

interface ProductOfferAvailabilityStorageWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferAvailabilityStorageCollectionByOmsProductReservationKeyEvents(array $eventTransfers): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferAvailabilityStorageCollectionByProductOfferStockKeyEvents(array $eventTransfers): void;

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferAvailabilityStorageCollectionByProductOfferKeyEvents(array $eventTransfers): void;
}
