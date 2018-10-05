<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Converter;

use Spryker\Shared\Money\Exception\InvalidConverterArgumentException;

class DecimalToIntegerConverter implements DecimalToIntegerConverterInterface
{
    public const PRICE_PRECISION = 100;

    /**
     * @param float $value
     *
     * @throws \Spryker\Shared\Money\Exception\InvalidConverterArgumentException
     *
     * @return int
     */
    public function convert($value)
    {
        if (!is_float($value)) {
            throw new InvalidConverterArgumentException(sprintf(
                'Only float values allowed for conversion to int. Current type is "%s"',
                gettype($value)
            ));
        }

        return (int)round($value * static::PRICE_PRECISION);
    }
}
