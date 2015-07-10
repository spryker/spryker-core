<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Currency\Config;

use SprykerFeature\Shared\Library\Currency\CurrencyInterface;

class CHF implements CurrencyInterface
{

    public function getIsoCode()
    {
        return 'CHF';
    }

    public function getSymbol()
    {
        return 'CHF';
    }

    public function getThousandsSeparator()
    {
        return '.';
    }

    public function getDecimalSeparator()
    {
        return ',';
    }

    public function getDecimalDigits()
    {
        return 2;
    }

    public function getFormatPattern()
    {
        return '{s} {v}';
    }

}
