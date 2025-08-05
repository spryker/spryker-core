<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\CompanyFile\Formatter;

use DateTime;
use DateTimeZone;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

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

    public function __construct(protected SelfServicePortalConfig $selfServicePortalConfig)
    {
    }

    public function formatToUTCFromLocalTimeZone(string $dateTime): string
    {
        $dateTime = new DateTime($dateTime, new DateTimeZone($this->selfServicePortalConfig->getDateTimeZone()));
        $dateTime->setTimezone(new DateTimeZone(static::DATE_TIME_ZONE_UTC));

        return $dateTime->format(static::DATE_TIME_FORMAT);
    }
}
