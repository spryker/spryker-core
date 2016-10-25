<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

use DateTimeZone;

interface DateFormatterInterface
{

    /**
     * @param string $date
     * @param \DateTimeZone|null $timezone
     *
     * @return string
     */
    public function dateShort($date, DateTimeZone $timezone = null);

    /**
     * @param string $date
     * @param \DateTimeZone|null $timezone
     *
     * @return string
     */
    public function dateMedium($date, DateTimeZone $timezone = null);

    /**
     * @param string $date
     * @param \DateTimeZone|null $timezone
     *
     * @return string
     */
    public function dateRFC($date, DateTimeZone $timezone = null);

    /**
     * @param string $date
     * @param \DateTimeZone|null $timezone
     *
     * @return string
     */
    public function dateTime($date, DateTimeZone $timezone = null);

}
