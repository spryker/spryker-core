<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Currency\Dependency\Internationalization;

use Symfony\Component\Intl\Currencies;

class CurrenciesToInternationalizationBridge implements CurrencyToInternationalizationInterface
{
    /**
     * @param string $isoCode
     *
     * @return string
     */
    public function getSymbolByIsoCode($isoCode)
    {
        return Currencies::getSymbol($isoCode);
    }

    /**
     * @param string $isoCode
     *
     * @return string
     */
    public function getNameByIsoCode($isoCode)
    {
        return Currencies::getName($isoCode);
    }

    /**
     * @param string $isoCode
     *
     * @return int|null
     */
    public function getFractionDigits($isoCode)
    {
        return Currencies::getFractionDigits($isoCode);
    }
}
