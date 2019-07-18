<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator\Type;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;

class PercentageType implements CalculatorTypeInterface
{
    /**
     * @var float
     */
    protected static $roundingError = 0.0;

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function calculateDiscount(array $discountableItems, DiscountTransfer $discountTransfer)
    {
        $value = $discountTransfer->requireAmount()->getAmount();

        $this->ensureIsValidNumber($value);

        $discountAmount = 0;

        $value = $value / 100;

        if ($value > 100) {
            $value = 100;
        }

        if ($value <= 0) {
            return 0;
        }

        foreach ($discountableItems as $discountableItemTransfer) {
            $itemTotalAmount = $discountableItemTransfer->getUnitPrice() * $this->getDiscountableObjectQuantity($discountableItemTransfer);
            $discountAmount += $this->calculateDiscountAmount($itemTotalAmount, $value);
        }

        if ($discountAmount <= 0) {
            return 0;
        }

        return $discountAmount;
    }

    /**
     * @param int $unitPrice
     * @param int $discountPercentage
     *
     * @return int
     */
    protected function calculateDiscountAmount($unitPrice, $discountPercentage)
    {
        $itemDiscountAmount = ($unitPrice * $discountPercentage / 100) + static::$roundingError;
        $itemDiscountAmountRounded = (int)round($itemDiscountAmount);
        static::$roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

        return $itemDiscountAmountRounded;
    }

    /**
     * @param float|int|null $number
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\CalculatorException
     *
     * @return void
     */
    protected function ensureIsValidNumber($number)
    {
        if (!is_float($number) && !is_int($number)) {
            throw new CalculatorException('Wrong value number, only float or integer is allowed.');
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return int
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
