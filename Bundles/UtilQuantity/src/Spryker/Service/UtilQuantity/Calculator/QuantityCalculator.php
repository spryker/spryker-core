<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity\Calculator;

class QuantityCalculator implements QuantityCalculatorInterface
{
    /**
     * @var \Spryker\Service\UtilQuantity\Calculator\PrecisionCalculatorInterface
     */
    protected $precisionCalculator;

    /**
     * @param \Spryker\Service\UtilQuantity\Calculator\PrecisionCalculatorInterface $precisionCalculator
     */
    public function __construct(PrecisionCalculatorInterface $precisionCalculator)
    {
        $this->precisionCalculator = $precisionCalculator;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    public function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        $resultQuantity = $firstQuantity + $secondQuantity;

        return round($resultQuantity, $this->precisionCalculator->getMaxPrecision($firstQuantity, $secondQuantity));
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    public function subtractQuantities(float $firstQuantity, float $secondQuantity): float
    {
        $resultQuantity = $firstQuantity - $secondQuantity;

        return round($resultQuantity, $this->precisionCalculator->getMaxPrecision($firstQuantity, $secondQuantity));
    }
}
