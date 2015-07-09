<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Currency\Config;

use SprykerFeature\Shared\Library\Currency\CurrencyInterface;

class PLN implements CurrencyInterface
{

    public function getIsoCode()
    {
        return 'PLN';
    }

    public function getSymbol()
    {
        return 'zł';
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
        return '{v} {s}';
    }

}
