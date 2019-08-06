<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

interface PriceProductScheduleDisablerInterface
{
    /**
     * @return void
     */
    public function disableNotActiveScheduledPrices(): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    public function disableNotRelevantPriceProductSchedulesByPriceProductSchedule(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): void;
}
