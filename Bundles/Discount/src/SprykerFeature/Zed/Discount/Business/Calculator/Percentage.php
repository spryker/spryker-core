<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Calculator;

use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

class Percentage implements CalculatorInterface
{
    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $percentage
     *
     * @return int
     */
    public function calculate(array $discountableObjects, $percentage)
    {
        $this->ensureIsValidNumber($percentage);

        $discountAmount = 0;

        if ($percentage > 100) {
            $percentage = 100;
        }

        if ($percentage <= 0) {
            return 0;
        }

        foreach ($discountableObjects as $discountableObject) {
            $itemTotalAmount = $discountableObject->getGrossPrice() * $discountableObject->getQuantity();
            $discountAmount += $this->calculateDiscountAmount($itemTotalAmount, $percentage);
        }


        if ($discountAmount <= 0) {
            return 0;
        }

        return round($discountAmount);
    }

    /**
     * @param int $grossPrice
     * @param int $number
     *
     * @return float
     */
    protected function calculateDiscountAmount($grossPrice, $number)
    {
        return round(($grossPrice * $number / 100), 2);
    }

    /**
     * @param float $number
     *
     * @throws \InvalidArgumentException
     */
    protected function ensureIsValidNumber($number)
    {
        if (!is_float($number) && !is_int($number)) {
            throw new \InvalidArgumentException('Wrong number, only float or integer is allowed!');
        }
    }

}
