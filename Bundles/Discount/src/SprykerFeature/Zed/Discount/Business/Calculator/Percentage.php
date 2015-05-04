<?php

namespace SprykerFeature\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\Discount\DependencyDiscountableItemInterfaceTransfer;

class Percentage
{
    /**
     * @param DiscountableItemInterface[] $discountableObjects
     * @param float $number
     * @return float
     */
    public function calculate(array $discountableObjects, $number)
    {
        $this->checkIsValidNumber($number);

        $discountAmount = 0;

        if ($number > 100) {
            $number = 100;
        }

        if ($number <= 0) {
            return 0;
        }

        foreach ($discountableObjects as $discountableObject) {
            $discountAmount += $this->calculateDiscountAmount($discountableObject, $number);
        }

        if ($discountAmount <= 0) {
            return 0;
        }

        return $discountAmount;
    }

    /**
     * @param DiscountableItemInterface $discountableObject
     * @param int $number
     * @return float
     */
    protected function calculateDiscountAmount(DiscountableItemInterface $discountableObject, $number)
    {
        return round(($discountableObject->getGrossPrice() * $number / 100), 2);
    }

    /**
     * @param float $number
     * @throws \InvalidArgumentException
     */
    protected function checkIsValidNumber($number)
    {
        if (!is_float($number) && !is_int($number)) {
            throw new \InvalidArgumentException('Wrong number');
        }
    }
}
