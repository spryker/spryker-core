<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity\Calculator;

class QuantityCalculator implements QuantityCalculatorInterface
{
    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    public function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        $resultQuantity = $firstQuantity + $secondQuantity;

        return round($resultQuantity, $this->getMaxPrecision($firstQuantity, $secondQuantity));
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

        return round($resultQuantity, $this->getMaxPrecision($firstQuantity, $secondQuantity));
    }

    /**
     * @param float $quantity
     *
     * @return int
     */
    protected function getQuantityPrecision(float $quantity): int
    {
        $stringQuantity = (string)$quantity;
        return strlen(substr($stringQuantity, strpos($stringQuantity, '.') + 1));
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return int
     */
    protected function getMaxPrecision(float $firstQuantity, float $secondQuantity): int
    {
        return max($this->getQuantityPrecision($firstQuantity), $this->getQuantityPrecision($secondQuantity));
    }
}
