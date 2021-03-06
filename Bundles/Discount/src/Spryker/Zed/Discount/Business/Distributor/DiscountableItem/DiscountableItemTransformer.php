<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Distributor\DiscountableItem;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;

class DiscountableItemTransformer implements DiscountableItemTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransformerTransfer
     */
    public function transformSplittableDiscountableItem(
        DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
    ): DiscountableItemTransformerTransfer {
        $roundingError = $discountableItemTransformerTransfer->getRoundingError();
        $discountableItemTransfer = $discountableItemTransformerTransfer->getDiscountableItem();
        $discountTransfer = $discountableItemTransformerTransfer->getDiscount();
        $totalDiscountAmount = $discountableItemTransformerTransfer->getTotalDiscountAmount();
        $totalAmount = $discountableItemTransformerTransfer->getTotalAmount();
        $quantity = $discountableItemTransformerTransfer->getQuantity();

        $calculatedDiscountTransfer = $this->createBaseCalculatedDiscountTransfer($discountTransfer);
        $singleItemAmountShare = $discountableItemTransfer->getUnitPrice() / $totalAmount;

        for ($i = 0; $i < $quantity; $i++) {
            if ($this->isAllOriginalItemCalculatedDiscountsAdded($calculatedDiscountTransfer, $discountableItemTransfer, $discountableItemTransformerTransfer)) {
                break;
            }

            $itemDiscountAmount = ($totalDiscountAmount * $singleItemAmountShare) + $roundingError;
            $itemDiscountAmountRounded = (int)round($itemDiscountAmount);
            $roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

            $distributedDiscountTransfer = clone $calculatedDiscountTransfer;
            $distributedDiscountTransfer->setIdDiscount($discountTransfer->getIdDiscount());
            $distributedDiscountTransfer->setSumAmount($itemDiscountAmountRounded);
            $distributedDiscountTransfer->setUnitAmount($itemDiscountAmountRounded);
            $distributedDiscountTransfer->setQuantity(1);

            $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->append($distributedDiscountTransfer);
        }

        $discountableItemTransformerTransfer
            ->setRoundingError($roundingError)
            ->setDiscountableItem($discountableItemTransfer);

        return $discountableItemTransformerTransfer;
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
     * Checks if all OriginalItemCalculatedDiscounts has been added and if it is not a Voucher code because it should be only Cart rule discount.
     *
     * @param \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
     *
     * @return bool
     */
    protected function isAllOriginalItemCalculatedDiscountsAdded(
        CalculatedDiscountTransfer $calculatedDiscountTransfer,
        DiscountableItemTransfer $discountableItemTransfer,
        DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
    ): bool {
        return (
            !$calculatedDiscountTransfer->getVoucherCode() &&
            $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->count() === $discountableItemTransformerTransfer->getQuantity()
        );
    }
}
