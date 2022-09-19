<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\UtilNumber;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface UtilNumberConstants
{
    /**
     * Specification:
     * - Defines format for the number format logic.
     * - @see {@link https://www.php.net/manual/en/class.numberformatter.php#intl.numberformatter-constants.types}
     *
     * @api
     *
     * @var string
     */
    public const UTIL_NUMBER_FORMAT_STYLE = 'UTIL_NUMBER:FORMAT_STYLE';

    /**
     * Specification:
     * - Defines max fraction digits.
     * - @see {@link https://www.php.net/manual/en/class.numberformatter.php}
     *
     * @api
     *
     * @var string
     */
    public const UTIL_NUMBER_MAX_FRACTION_DIGITS = 'UTIL_NUMBER:MAX_FRACTION_DIGITS';
}
