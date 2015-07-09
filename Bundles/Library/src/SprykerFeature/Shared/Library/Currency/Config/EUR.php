<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Currency\Config;

use SprykerFeature\Shared\Library\Currency\CurrencyInterface;

class EUR implements CurrencyInterface
{

    public function getIsoCode()
    {
        return 'EUR';
    }

    public function getSymbol()
    {
        return '€';
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
