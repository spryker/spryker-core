<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Business;

interface MerchantOpeningHoursStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes changes to storage by merchant opening hours weekday schedule events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantOpeningHoursWeekdayScheduleEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes changes to storage by merchant opening hours date schedule events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantOpeningHoursDateScheduleEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes merchant opening hours data to storage by merchant opening hours events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantOpeningHoursEvents(array $eventEntityTransfers): void;
}
