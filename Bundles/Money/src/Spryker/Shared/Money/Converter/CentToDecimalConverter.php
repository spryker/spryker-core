<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Converter;

class CentToDecimalConverter implements CentToDecimalConverterInterface
{

    const PRICE_PRECISION = 100;

    /**
     * @param int $value
     *
     * @return float
     */
    public function convert($value)
    {
        return (float)number_format($value / self::PRICE_PRECISION, 2, '.', '');
    }

}
