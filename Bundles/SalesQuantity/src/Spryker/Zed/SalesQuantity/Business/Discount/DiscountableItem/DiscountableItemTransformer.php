<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Discount\DiscountableItem;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;

class DiscountableItemTransformer implements DiscountableItemTransformerInterface
{
    /**
     * @uses \Spryker\Zed\Discount\DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE
     *
     * @var string
     */
    protected const PLUGIN_CALCULATOR_PERCENTAGE = 'PLUGIN_CALCULATOR_PERCENTAGE';

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransformerTransfer
     */
    public function transformNonSplittableDiscountableItem(
        DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
    ): DiscountableItemTransformerTransfer {
        $roundingError = $discountableItemTransformerTransfer->getRoundingError();
        $discountableItemTransfer = $discountableItemTransformerTransfer->getDiscountableItem();
        $discountTransfer = $discountableItemTransformerTransfer->getDiscount();
        $totalDiscountAmount = $discountableItemTransformerTransfer->getTotalDiscountAmount();
        $totalAmount = $discountableItemTransformerTransfer->getTotalAmount();
        $quantity = $discountableItemTransformerTransfer->getQuantity();

        $calculatedDiscountTransfer = $this->createBaseCalculatedDiscountTransfer($discountTransfer);

        $iterationUnitPrice = (int)$discountableItemTransfer->getUnitPrice();
        if ($this->isDiscountPriorityIterationApplicable($discountableItemTransfer, $discountTransfer)) {
            $iterationUnitPrice = $this->calculatePriorityIterationUnitPrice($discountableItemTransfer, $discountTransfer, $iterationUnitPrice);
        }

        $singleItemAmountShare = $iterationUnitPrice * $quantity / $totalAmount;

        $itemDiscountAmount = ($totalDiscountAmount * $singleItemAmountShare) + $roundingError;
        $itemDiscountAmountRounded = $this->roundItemDiscountAmount($itemDiscountAmount);
        $roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

        $distributedDiscountTransfer = clone $calculatedDiscountTransfer;
        $distributedDiscountTransfer->setIdDiscount($discountTransfer->getIdDiscount());
        $distributedDiscountTransfer->setSumAmount($itemDiscountAmountRounded);
        $distributedDiscountTransfer->setUnitAmount((int)round($itemDiscountAmountRounded / $quantity));
        $distributedDiscountTransfer->setQuantity($quantity);

        $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->append($distributedDiscountTransfer);

        $discountableItemTransformerTransfer
            ->setRoundingError($roundingError)
            ->setDiscountableItem($discountableItemTransfer);

        return $discountableItemTransformerTransfer;
    }

    /**
     * @param float $itemDiscountAmount
     *
     * @return int
     */
    protected function roundItemDiscountAmount(float $itemDiscountAmount): int
    {
        if ($itemDiscountAmount > 0 && $itemDiscountAmount < 1) {
            return 1;
        }

        return (int)round($itemDiscountAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected function createBaseCalculatedDiscountTransfer(DiscountTransfer $discountTransfer): CalculatedDiscountTransfer
    {
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->fromArray($discountTransfer->toArray(), true);

        return $calculatedDiscountTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $iterationUnitPrice
     *
     * @return int
     */
    protected function calculatePriorityIterationUnitPrice(
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountTransfer $discountTransfer,
        int $iterationUnitPrice
    ): int {
        foreach ($discountableItemTransfer->getOriginalItemCalculatedDiscounts() as $calculatedDiscountTransfer) {
            if ($calculatedDiscountTransfer->getPriority() < $discountTransfer->getPriority()) {
                $iterationUnitPrice -= $calculatedDiscountTransfer->getUnitAmount();
            }
        }

        return $iterationUnitPrice;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return bool
     */
    protected function isDiscountPriorityIterationApplicable(
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountTransfer $discountTransfer
    ): bool {
        if ($discountTransfer->getPriority() !== null && $discountTransfer->getCalculatorPlugin() !== static::PLUGIN_CALCULATOR_PERCENTAGE) {
            return false;
        }

        foreach ($discountableItemTransfer->getOriginalItemCalculatedDiscounts() as $calculatedDiscountTransfer) {
            if ($calculatedDiscountTransfer->getPriority() !== null) {
                return true;
            }
        }

        return false;
    }
}
