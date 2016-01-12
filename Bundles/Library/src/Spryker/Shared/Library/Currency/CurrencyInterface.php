<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Currency;

interface CurrencyInterface
{

    public function getIsoCode();

    public function getSymbol();

    public function getThousandsSeparator();

    public function getDecimalSeparator();

    public function getDecimalDigits();

    public function getFormatPattern();

}
