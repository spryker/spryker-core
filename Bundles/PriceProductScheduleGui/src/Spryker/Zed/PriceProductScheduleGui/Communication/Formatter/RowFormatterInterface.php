<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Formatter;

use DateTime;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;

interface RowFormatterInterface
{
    /**
     * @param int $amount
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     *
     * @return string
     */
    public function formatMoney(int $amount, SpyPriceProductSchedule $priceProductScheduleEntity): string;

    /**
     * @param \DateTime $dateTime
     *
     * @return string
     */
    public function formatDateTime(DateTime $dateTime): string;
}
