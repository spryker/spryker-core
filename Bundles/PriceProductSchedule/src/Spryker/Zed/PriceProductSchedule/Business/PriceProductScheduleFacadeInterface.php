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
     * - Apply all scheduled prices.
     *
     * @api
     *
     * @return void
     */
    public function applyScheduledPrices(): void;

    /**
     * Specification:
     * - Delete scheduled prices that has been applied earlier than the days provided as parameter
     *
     * @api
     *
     * @param int $daysRetained
     *
     * @return void
     */
    public function cleanAppliedScheduledPrices(int $daysRetained): void;
}
