<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Dependency\Parser;

interface MoneyToParserInterface
{
    /**
     * @param string $money
     * @param string $isoCode
     *
     * @return \Money\Money
     */
    public function parse($money, $isoCode);
}
