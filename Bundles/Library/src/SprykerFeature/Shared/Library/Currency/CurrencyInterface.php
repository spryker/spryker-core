<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Currency;

interface CurrencyInterface
{

    const PLACEHOLDER_VALUE = '{v}';

    const PLACEHOLDER_SYMBOL = '{s}';

    public function getIsoCode();

    public function getSymbol();

    public function getThousandsSeparator();

    public function getDecimalSeparator();

    public function getDecimalDigits();

    public function getFormatPattern();

}
