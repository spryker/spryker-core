<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator\Type;

use Generated\Shared\Transfer\DiscountableItemTransfer;

class Percentage implements CalculatorInterface
{

    /**
     * @param DiscountableItemTransfer[] $discountableItems
     * @param float $percentage
     *
     * @return int
     */
    public function calculate(array $discountableItems, $percentage)
    {
        $this->ensureIsValidNumber($percentage);

        $discountAmount = 0;

        if ($percentage > 100) {
            $percentage = 100;
        }

        if ($percentage <= 0) {
            return 0;
        }

        foreach ($discountableItems as $discountableItemTransfer) {
            $itemTotalAmount = $discountableItemTransfer->getUnitGrossPrice() * $this->getDiscountableObjectQuantity($discountableItemTransfer);
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
     *
     * @return void
     */
    protected function ensureIsValidNumber($number)
    {
        if (!is_float($number) && !is_int($number)) {
            throw new \InvalidArgumentException('Wrong percentage number, only float or integer is allowed.');
        }
    }

    /**
     * @param DiscountableItemTransfer $discountableItemTransfer
     *
     * @return mixed
     */
    protected function getDiscountableObjectQuantity(DiscountableItemTransfer $discountableItemTransfer)
    {
        $quantity = $discountableItemTransfer->getQuantity();

        if (empty($quantity)) {
            return 1;
        }

        return $quantity;
    }

}
