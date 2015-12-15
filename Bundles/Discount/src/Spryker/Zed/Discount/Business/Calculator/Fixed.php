<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use Spryker\Zed\Discount\Business\Model\DiscountableInterface;

class Fixed implements CalculatorInterface
{

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $percentage
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $percentage)
    {
        if ($percentage <= 0) {
            return 0;
        }

        return $percentage;
    }

}
