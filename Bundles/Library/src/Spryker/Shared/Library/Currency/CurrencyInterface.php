<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Currency;

interface CurrencyInterface
{

    /**
     * @return string
     */
    public function getIsoCode();

    /**
     * @return string
     */
    public function getSymbol();

    /**
     * @return string
     */
    public function getThousandsSeparator();

    /**
     * @return string
     */
    public function getDecimalSeparator();

    /**
     * @return int
     */
    public function getDecimalDigits();

    /**
     * @return string
     */
    public function getFormatPattern();

}
