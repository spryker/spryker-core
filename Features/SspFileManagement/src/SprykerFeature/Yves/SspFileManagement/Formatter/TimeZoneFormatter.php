<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement\Formatter;

use DateTime;
use DateTimeZone;
use SprykerFeature\Yves\SspFileManagement\SspFileManagementConfig;

class TimeZoneFormatter implements TimeZoneFormatterInterface
{
    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d\TH:i';

    /**
     * @var string
     */
    protected const DATE_TIME_ZONE_UTC = 'UTC';

    /**
     * @param \SprykerFeature\Yves\SspFileManagement\SspFileManagementConfig $sspFileManagementConfig
     */
    public function __construct(protected SspFileManagementConfig $sspFileManagementConfig)
    {
    }

    /**
     * @param string $dateTime
     *
     * @return string
     */
    public function formatToUTCFromLocalTimeZone(string $dateTime): string
    {
        $dateTime = new DateTime($dateTime, new DateTimeZone($this->sspFileManagementConfig->getDateTimeZone()));
        $dateTime->setTimezone(new DateTimeZone(static::DATE_TIME_ZONE_UTC));

        return $dateTime->format(static::DATE_TIME_FORMAT);
    }
}
