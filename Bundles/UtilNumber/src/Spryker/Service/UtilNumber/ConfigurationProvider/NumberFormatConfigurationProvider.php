<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber\ConfigurationProvider;

use Generated\Shared\Transfer\NumberFormatConfigTransfer;
use Generated\Shared\Transfer\NumberFormatFilterTransfer;
use NumberFormatter;
use Spryker\Service\UtilNumber\Formatter\NumberFormatterFactoryInterface;

class NumberFormatConfigurationProvider implements NumberFormatConfigurationProviderInterface
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
     * @param string|null $locale
     *
     * @return \Generated\Shared\Transfer\NumberFormatConfigTransfer
     */
    public function getNumberFormatConfig(?string $locale = null): NumberFormatConfigTransfer
    {
        $numberFormatter = $this->numberFormatterFactory->createIntlNumberFormatter(
            (new NumberFormatFilterTransfer())->setLocale($locale),
        );

        return (new NumberFormatConfigTransfer())
            ->setGroupingSeparatorSymbol((string)$numberFormatter->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL))
            ->setDecimalSeparatorSymbol((string)$numberFormatter->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL));
    }
}
