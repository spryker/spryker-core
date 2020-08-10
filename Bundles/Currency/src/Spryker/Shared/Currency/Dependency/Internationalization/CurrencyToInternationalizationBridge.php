<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Dependency\Internationalization;

use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Intl;

class CurrencyToInternationalizationBridge implements CurrencyToInternationalizationInterface
{
    /**
     * @param string $isoCode
     *
     * @return string
     */
    public function getSymbolByIsoCode($isoCode)
    {
        if (method_exists(Intl::class, 'getCurrencyBundle')) {
            return Intl::getCurrencyBundle()->getCurrencySymbol($isoCode);
        }

        return Currencies::getSymbol($isoCode);
    }

    /**
     * @param string $isoCode
     *
     * @return string
     */
    public function getNameByIsoCode($isoCode)
    {
        if (method_exists(Intl::class, 'getCurrencyBundle')) {
            return Intl::getCurrencyBundle()->getCurrencyName($isoCode);
        }

        return Currencies::getName($isoCode);
    }

    /**
     * @param string $isoCode
     *
     * @return int|null
     */
    public function getFractionDigits($isoCode)
    {
        if (method_exists(Intl::class, 'getCurrencyBundle')) {
            return Intl::getCurrencyBundle()->getFractionDigits($isoCode);
        }

        return Currencies::getFractionDigits($isoCode);
    }
}
