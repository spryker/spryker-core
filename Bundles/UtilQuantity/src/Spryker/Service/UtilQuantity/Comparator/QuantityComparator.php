<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity\Comparator;

use Spryker\Service\UtilQuantity\Calculator\PrecisionCalculatorInterface;

class QuantityComparator implements QuantityComparatorInterface
{
    protected const EPSILON = 0.00001;

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
     * @return bool
     */
    public function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return abs($firstQuantity - $secondQuantity) < static::EPSILON;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantityModuloEqual(float $firstQuantity, float $secondQuantity): bool
    {
        $maxPrecision = $this->precisionCalculator->getMaxPrecision($firstQuantity, $secondQuantity);

        $intFirstQuantity = (int)($firstQuantity * pow(10, $maxPrecision));
        $intSecondQuantity = (int)($secondQuantity * pow(10, $maxPrecision));

        return $intFirstQuantity % $intSecondQuantity === 0;
    }
}
