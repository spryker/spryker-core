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
     * - Publishes merchant opening hours changes to storage.
     *
     * @api
     *
     * @param int[] $merchantIds
     *
     * @return void
     */
    public function publish(array $merchantIds): void;

    /**
     * Specification:
     * - Publishes merchant opening hours changes to storage on weekday schedule create.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function publishWeekdayScheduleCreate(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes merchant opening hours changes to storage on date schedule create.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function publishDateScheduleCreate(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Publishes merchant opening hours changes to storage on schedule publish.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function publishMerchantOpeningHours(array $eventEntityTransfers): void;
}
