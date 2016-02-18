<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
