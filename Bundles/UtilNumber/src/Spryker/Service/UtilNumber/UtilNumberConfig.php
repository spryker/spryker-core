<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber;

use NumberFormatter;
use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\UtilNumber\UtilNumberConstants;

class UtilNumberConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const DEFAULT_NUMBER_FORMAT_STYLE = NumberFormatter::DEFAULT_STYLE;

    /**
     * @var int
     */
    protected const DEFAULT_MAX_FRACTION_DIGITS = 10;

    /**
     * Specification:
     * - Returns number formatter style.
     * - Uses `UtilNumberConfig::DEFAULT_NUMBER_FORMAT_STYLE` as a fallback.
     * - @see {@link https://www.php.net/manual/en/class.numberformatter.php#intl.numberformatter-constants.unumberformatstyle}
     *
     * @api
     *
     * @return int
     */
    public function getNumberFormatStyle(): int
    {
        return $this->get(UtilNumberConstants::UTIL_NUMBER_FORMAT_STYLE, static::DEFAULT_NUMBER_FORMAT_STYLE);
    }

    /**
     * Specification:
     * - Returns max fraction digits.
     * - Uses `UtilNumberConfig::DEFAULT_MAX_FRACTION_DIGITS` as a fallback.
     * - @see {@link https://www.php.net/manual/en/class.numberformatter.php}
     *
     * @api
     *
     * @return int
     */
    public function getMaxFractionDigits(): int
    {
        return $this->get(UtilNumberConstants::UTIL_NUMBER_MAX_FRACTION_DIGITS, static::DEFAULT_MAX_FRACTION_DIGITS);
    }
}
