<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity\Comparator;

use Spryker\Service\UtilQuantity\Calculator\PrecisionCalculatorInterface;
use Spryker\Service\UtilQuantity\Converter\QuantityConverterInterface;

class QuantityComparator implements QuantityComparatorInterface
{
    protected const EPSILON = 0.00001;

    /**
     * @var \Spryker\Service\UtilQuantity\Calculator\PrecisionCalculatorInterface
     */
    protected $precisionCalculator;

    /**
     * @var \Spryker\Service\UtilQuantity\Converter\QuantityConverterInterface
     */
    protected $quantityConverter;

    /**
     * @param \Spryker\Service\UtilQuantity\Calculator\PrecisionCalculatorInterface $precisionCalculator
     * @param \Spryker\Service\UtilQuantity\Converter\QuantityConverterInterface $quantityConverter
     */
    public function __construct(
        PrecisionCalculatorInterface $precisionCalculator,
        QuantityConverterInterface $quantityConverter
    ) {
        $this->precisionCalculator = $precisionCalculator;
        $this->quantityConverter = $quantityConverter;
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

        $intDividendQuantity = $this->quantityConverter->convertWithExponentialBase($dividendQuantity, $maxPrecision);
        $intDivisorQuantity = $this->quantityConverter->convertWithExponentialBase($divisorQuantity, $maxPrecision);

        return $this->isQuantityEqual(
            $intDividendQuantity % $intDivisorQuantity,
            $remainder
        );
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantityGreaterOrEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $firstQuantity - $secondQuantity > static::EPSILON || $this->isQuantityEqual($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantityLessOrEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $secondQuantity - $firstQuantity > static::EPSILON || $this->isQuantityEqual($firstQuantity, $secondQuantity);
    }
}
