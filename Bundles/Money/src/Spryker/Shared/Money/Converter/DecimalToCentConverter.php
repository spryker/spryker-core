<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Converter;

class DecimalToCentConverter implements DecimalToCentConverterInterface
{

    const PRICE_PRECISION = 100;

    /**
     * @param float $value
     *
     * @return int
     */
    public function convert($value)
    {
        return (int)($value * self::PRICE_PRECISION);
    }

}
