<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business;

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
}
