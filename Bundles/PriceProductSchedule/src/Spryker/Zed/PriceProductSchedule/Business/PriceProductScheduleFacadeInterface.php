<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business;

use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;

interface PriceProductScheduleFacadeInterface
{
    /**
     * Specification:
     * - Applies scheduled prices for current store.
     * - Persists price product store for applied scheduled price product.
     * - Disables not relevant price product schedules for applied scheduled price products.
     * - Reverts price products from the fallback price types for scheduled product prices that are finished.
     *
     * @api
     *
     * @return void
     */
    public function applyScheduledPrices(): void;

    /**
     * Specification:
     * - Deletes scheduled prices that has been ended earlier than the days provided as parameter.
     *
     * @api
     *
     * @param int $daysRetained
     *
     * @return void
     */
    public function cleanAppliedScheduledPrices(int $daysRetained): void;

    /**
     * Specification:
     * - Creates and saves price product schedule list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function createPriceProductScheduleList(PriceProductScheduleListTransfer $priceProductScheduleListTransfer): PriceProductScheduleListResponseTransfer;

    /**
     * Specification:
     * - Updates and saves price product schedule list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function updatePriceProductScheduleList(PriceProductScheduleListTransfer $priceProductScheduleListTransfer): PriceProductScheduleListResponseTransfer;

    /**
     * Specification:
     * - Imports price product schedules.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    public function importPriceProductSchedules(PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest): PriceProductScheduleListImportResponseTransfer;
}
