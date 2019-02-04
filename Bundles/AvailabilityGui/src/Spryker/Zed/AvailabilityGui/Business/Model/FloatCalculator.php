<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Business\Model;

class FloatCalculator implements FloatCalculatorInterface
{
    /**
     * @var int
     */
    protected $precision;

    /**
     * @param $precision
     */
    public function __construct($precision = 2)
    {
        $this->precision = $precision;
    }

    /**
     * @param float $leftOperand
     * @param float $rightOperand
     *
     * @return int
     */
    public function compare(float $leftOperand, float $rightOperand): int
    {
        return bccomp($leftOperand, $rightOperand, $this->precision);
    }
}
