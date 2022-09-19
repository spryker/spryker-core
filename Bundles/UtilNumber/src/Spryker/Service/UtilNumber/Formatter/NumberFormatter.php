<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber\Formatter;

use Generated\Shared\Transfer\NumberFormatFloatRequestTransfer;
use Generated\Shared\Transfer\NumberFormatIntRequestTransfer;

class NumberFormatter implements NumberFormatterInterface
{
    /**
     * @var \Spryker\Service\UtilNumber\Formatter\NumberFormatterFactoryInterface
     */
    protected NumberFormatterFactoryInterface $numberFormatterFactory;

    /**
     * @param \Spryker\Service\UtilNumber\Formatter\NumberFormatterFactoryInterface $numberFormatterFactory
     */
    public function __construct(NumberFormatterFactoryInterface $numberFormatterFactory)
    {
        $this->numberFormatterFactory = $numberFormatterFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer
     *
     * @return string
     */
    public function formatInt(NumberFormatIntRequestTransfer $numberFormatIntRequestTransfer): string
    {
        return (string)$this->numberFormatterFactory
            ->createIntlNumberFormatter($numberFormatIntRequestTransfer->getNumberFormatFilterOrFail())
            ->format($numberFormatIntRequestTransfer->getNumberOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer
     *
     * @return string
     */
    public function formatFloat(NumberFormatFloatRequestTransfer $numberFormatFloatRequestTransfer): string
    {
        return (string)$this->numberFormatterFactory
            ->createIntlNumberFormatter($numberFormatFloatRequestTransfer->getNumberFormatFilterOrFail())
            ->format($numberFormatFloatRequestTransfer->getNumberOrFail());
    }
}
