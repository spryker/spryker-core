<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\UtilDateTime;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface UtilDateTimeConstants
{
    /**
     * Specification:
     * - Configures the used DateTimeZone for formatting
     * - @see http://php.net/manual/en/class.datetimezone.php
     *
     * @api
     */
    public const DATE_TIME_ZONE = 'DATE_TIME_ZONE';

    /**
     * Specification:
     * - Configures the used format for a date
     * - @see http://php.net/manual/en/datetime.formats.php
     *
     * @api
     */
    public const DATE_TIME_FORMAT_DATE = 'DATE_TIME_FORMAT_DATE';

    /**
     * Specification:
     * - Configures the used format for date and time
     * - @see http://php.net/manual/en/datetime.formats.php
     *
     * @api
     */
    public const DATE_TIME_FORMAT_DATE_TIME = 'DATE_TIME_FORMAT_DATE_TIME';

    /**
     * Specification:
     * - Configures the used format for time
     * - @see http://php.net/manual/en/datetime.formats.php
     *
     * @api
     */
    public const DATE_TIME_FORMAT_TIME = 'DATE_TIME_FORMAT_TIME';
}
