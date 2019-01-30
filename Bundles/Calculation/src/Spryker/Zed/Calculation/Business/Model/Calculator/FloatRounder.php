<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

class FloatRounder implements FloatRounderInterface
{
    public const DEFAULT_PRECISION = 0;

    /**
     * @var int
     */
    protected $roundMode;

    /**
     * @param int $precision
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
        return round($number, static::DEFAULT_PRECISION, $this->roundMode);
    }
}
