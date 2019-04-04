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
     * @param float $dividendQuantity
     * @param float $divisorQuantity
     * @param float $remainder
     *
     * @return bool
     */
    public function isQuantityModuloEqual(float $dividendQuantity, float $divisorQuantity, float $remainder): bool
    {
        $maxPrecision = $this->precisionCalculator->getMaxPrecision($dividendQuantity, $divisorQuantity);

        $intDividentQuantity = (int)($dividendQuantity * pow(10, $maxPrecision));
        $intDivisorQuantity = (int)($divisorQuantity * pow(10, $maxPrecision));

        return $this->isQuantityEqual(
            $intDividentQuantity % $intDivisorQuantity,
            $remainder
        );
    }
}
