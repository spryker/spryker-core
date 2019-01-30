<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @var int
     */
    protected $roundMode;

    /**
     * @param int $roundMode
     */
    public function __construct(int $roundMode = PHP_ROUND_HALF_UP)
    {
        $this->roundMode = $roundMode;
    }

    /**
     * @param float $number
     *
     * @return int
     */
    public function round(float $number): int
    {
        return (int)round($number, 0, $this->roundMode);
    }
}
