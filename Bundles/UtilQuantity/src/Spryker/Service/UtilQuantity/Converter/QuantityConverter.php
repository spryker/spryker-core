<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity\Converter;

class QuantityConverter implements QuantityConverterInterface
{
    /**
     * @param float $value
     * @param int $precision
     *
     * @return int
     */
    public function convertToInt(float $value, int $precision): int
    {
        return (int)round($value * pow(10, $precision));
    }
}
