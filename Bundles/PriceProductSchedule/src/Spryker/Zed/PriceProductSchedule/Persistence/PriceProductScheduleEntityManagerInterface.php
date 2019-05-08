<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductSchedulePersistenceFactory getFactory()
 */
interface PriceProductScheduleEntityManagerInterface
{
    /**
     * @param int $daysRetained
     *
     * @return void
     */
    public function deleteOldScheduledPrices(int $daysRetained): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function savePriceProductSchedule(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer;
}
