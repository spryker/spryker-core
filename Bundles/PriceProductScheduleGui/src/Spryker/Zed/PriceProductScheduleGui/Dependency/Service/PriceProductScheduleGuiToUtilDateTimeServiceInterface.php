<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Service;

use DateTime;

interface PriceProductScheduleGuiToUtilDateTimeServiceInterface
{
    /**
     * @param \DateTime|string $date
     * @param string $format
     *
     * @return string
     */
    public function formatDateTimeToCustomFormat(DateTime|string $date, string $format): string;
}
