<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber\Formatter;

use Generated\Shared\Transfer\NumberFormatFilterTransfer;
use NumberFormatter as IntlNumberFormatter;
use Spryker\Service\UtilNumber\UtilNumberConfig;

class NumberFormatterFactory implements NumberFormatterFactoryInterface
{
    /**
     * @var \Spryker\Service\UtilNumber\UtilNumberConfig
     */
    protected UtilNumberConfig $utilNumberConfig;

    /**
     * @param \Spryker\Service\UtilNumber\UtilNumberConfig $utilNumberConfig
     */
    public function __construct(UtilNumberConfig $utilNumberConfig)
    {
        $this->utilNumberConfig = $utilNumberConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\NumberFormatFilterTransfer $numberFormatFilterTransfer
     *
     * @return \NumberFormatter
     */
    public function createIntlNumberFormatter(NumberFormatFilterTransfer $numberFormatFilterTransfer): IntlNumberFormatter
    {
        $numberFormatStyle = $numberFormatFilterTransfer->getNumberFormatStyle() ?? $this->utilNumberConfig->getNumberFormatStyle();

        $numberFormatter = new IntlNumberFormatter(
            (string)$numberFormatFilterTransfer->getLocale(),
            $numberFormatStyle,
        );

        return $this->configureIntlNumberFormatterAttributes($numberFormatter, $numberFormatFilterTransfer);
    }

    /**
     * @param \NumberFormatter $numberFormatter
     * @param \Generated\Shared\Transfer\NumberFormatFilterTransfer $numberFormatFilterTransfer
     *
     * @return \NumberFormatter
     */
    protected function configureIntlNumberFormatterAttributes(
        IntlNumberFormatter $numberFormatter,
        NumberFormatFilterTransfer $numberFormatFilterTransfer
    ): IntlNumberFormatter {
        $maxFractionDigits = $numberFormatFilterTransfer->getMaxFractionDigits() ?? $this->utilNumberConfig->getMaxFractionDigits();

        $numberFormatter->setAttribute(
            IntlNumberFormatter::MAX_FRACTION_DIGITS,
            $maxFractionDigits,
        );

        return $numberFormatter;
    }
}
