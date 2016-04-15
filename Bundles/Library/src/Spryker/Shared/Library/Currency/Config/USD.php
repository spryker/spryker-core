<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Currency\Config;

use Spryker\Shared\Library\Currency\CurrencyInterface;

class USD implements CurrencyInterface
{

    /**
     * @return string
     */
    public function getIsoCode()
    {
        return 'USD';
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return '$';
    }

    /**
     * @return string
     */
    public function getThousandsSeparator()
    {
        return ',';
    }

    /**
     * @return string
     */
    public function getDecimalSeparator()
    {
        return '.';
    }

    /**
     * @return int
     */
    public function getDecimalDigits()
    {
        return 2;
    }

    /**
     * @return string
     */
    public function getFormatPattern()
    {
        return '{s}{v}';
    }

}
