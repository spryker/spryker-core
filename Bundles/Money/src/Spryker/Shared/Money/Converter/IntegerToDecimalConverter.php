<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Converter;

use Spryker\Shared\Money\Exception\InvalidConverterArgumentException;

class IntegerToDecimalConverter implements IntegerToDecimalConverterInterface
{
    public const PRICE_PRECISION = 100;

    /**
     * @param int $value
     *
     * @throws \Spryker\Shared\Money\Exception\InvalidConverterArgumentException
     *
     * @return float
     */
    public function convert($value)
    {
        if (!is_int($value)) {
            throw new InvalidConverterArgumentException(sprintf(
                'Only integer values allowed for conversion to float. Current type is "%s"',
                gettype($value)
            ));
        }

        return (float)bcdiv($value, static::PRICE_PRECISION, 2);
    }
}
