<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Calculator;

class Fixed
{

    /**
     * @param DiscountItemInterface[] $discountableObjects
     * @param float $number
     *
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
