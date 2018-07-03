<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Discount\DiscountableItem;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;

class DiscountableItemTransformer implements DiscountableItemTransformerInterface
{
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
        $singleItemAmountShare = $discountableItemTransfer->getUnitPrice() * $quantity / $totalAmount;

        $itemDiscountAmount = ($totalDiscountAmount * $singleItemAmountShare) + $roundingError;
        $itemDiscountAmountRounded = (int)round($itemDiscountAmount);
        $roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

        $distributedDiscountTransfer = clone $calculatedDiscountTransfer;
        $distributedDiscountTransfer->setIdDiscount($discountTransfer->getIdDiscount());
        $distributedDiscountTransfer->setUnitAmount($itemDiscountAmountRounded);
        $distributedDiscountTransfer->setQuantity(1);

        $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->append($distributedDiscountTransfer);

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
}
