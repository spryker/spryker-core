<?php

namespace SprykerFeature\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\Discount\DependencyDiscountItemInterfaceTransfer;

class Fixed
{

    /**
     * @param DiscountItemInterface[] $discountableObjects
     * @param float $number
     * @return float
     */
    public function calculate(array $discountableObjects, $number)
    {
        if ($number <= 0) {
            return 0;
        }

        return $number;
    }
}
