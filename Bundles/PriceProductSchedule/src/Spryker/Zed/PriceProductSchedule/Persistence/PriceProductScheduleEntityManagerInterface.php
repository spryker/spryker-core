<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

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
    public function deleteAppliedScheduledPrices(int $daysRetained): void;
}
