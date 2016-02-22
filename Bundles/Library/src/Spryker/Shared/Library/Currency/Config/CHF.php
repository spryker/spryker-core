<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Currency\Config;

use Spryker\Shared\Library\Currency\CurrencyInterface;

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
