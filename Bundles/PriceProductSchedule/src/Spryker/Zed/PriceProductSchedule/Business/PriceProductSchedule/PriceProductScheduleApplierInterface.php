<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

interface PriceProductScheduleApplierInterface
{
    /**
     * @param string|null $storeName
     *
     * @return void
     */
    public function applyScheduledPrices(?string $storeName = null): void;
}
