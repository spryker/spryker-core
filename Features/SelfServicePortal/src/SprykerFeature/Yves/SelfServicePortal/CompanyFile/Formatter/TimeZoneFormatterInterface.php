<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\CompanyFile\Formatter;

interface TimeZoneFormatterInterface
{
    /**
     * @param string $dateTime
     *
     * @return string
     */
    public function formatToUTCFromLocalTimeZone(string $dateTime): string;
}
