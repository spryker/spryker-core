<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Converter;

interface IntegerToDecimalConverterInterface
{
    /**
     * @param int $value
     *
     * @return float
     */
    public function convert($value);
}
