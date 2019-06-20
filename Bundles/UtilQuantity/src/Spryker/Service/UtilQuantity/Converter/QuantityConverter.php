<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity\Converter;

class QuantityConverter implements QuantityConverterInterface
{
    protected const EXPONENTIAL_BASE = 10;

    /**
     * @param float $value
     * @param int $precision
     *
     * @return int
     */
    public function convertWithExponentialBase(float $value, int $precision): int
    {
        return (int)round($value * pow(static::EXPONENTIAL_BASE, $precision));
    }
}
