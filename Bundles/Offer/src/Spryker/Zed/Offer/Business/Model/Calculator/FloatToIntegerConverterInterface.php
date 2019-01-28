<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model\Calculator;

interface FloatToIntegerConverterInterface
{
    /**
     * @param float $amount
     * @param int $precision
     * @param int $mode
     *
     * @return int
     */
    public function convert(float $amount, int $precision = 0, int $mode = PHP_ROUND_HALF_UP): int;
}
