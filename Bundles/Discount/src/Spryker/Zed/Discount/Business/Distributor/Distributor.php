<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Distributor;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;

class Distributor implements DistributorInterface
{
    /**
     * @var float
     */
    protected $roundingError = 0.0;

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return void
     */
    public function distributeDiscountAmountToDiscountableItems(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        $totalAmount = $this->getTotalAmountOfDiscountableObjects($collectedDiscountTransfer);
        if ($totalAmount <= 0) {
            return;
        }

        $totalDiscountAmount = $collectedDiscountTransfer->getDiscount()->getAmount();
        if ($totalDiscountAmount <= 0) {
            return;
        }

        // There should not be a discount that is higher than the total gross price of all discountable objects
        if ($totalDiscountAmount > $totalAmount) {
            $totalDiscountAmount = $totalAmount;
        }

        $calculatedDiscountTransfer = $this->createBaseCalculatedDiscountTransfer($collectedDiscountTransfer->getDiscount());

        foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItemTransfer) {
            $quantity = $this->getDiscountableItemQuantity($discountableItemTransfer);
            $lienItemAmountShare = $discountableItemTransfer->getUnitPrice() * $quantity / $totalAmount;

 //           for ($i = 0; $i < $quantity; $i++) {
                $itemDiscountAmount = ($totalDiscountAmount * $lienItemAmountShare) + $this->roundingError;
                $itemDiscountAmountRounded = (int)round($itemDiscountAmount);
                $this->roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

                $distributedDiscountTransfer = clone $calculatedDiscountTransfer;
                $distributedDiscountTransfer->setIdDiscount($collectedDiscountTransfer->getDiscount()->getIdDiscount());
                $distributedDiscountTransfer->setUnitAmount($itemDiscountAmountRounded);
                $distributedDiscountTransfer->setQuantity(1);

                $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->append($distributedDiscountTransfer);
            }
   //     }
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return int
     */
    protected function getTotalAmountOfDiscountableObjects(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        $totalGrossAmount = 0;
        foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItemTransfer) {
            $totalGrossAmount += $discountableItemTransfer->getUnitPrice() *
                $this->getDiscountableItemQuantity($discountableItemTransfer);
        }

        return $totalGrossAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return int
     */
    protected function getDiscountableItemQuantity(DiscountableItemTransfer $discountableItemTransfer)
    {
        $quantity = 1;
        if ($discountableItemTransfer->getQuantity()) {
            $quantity = $discountableItemTransfer->getQuantity();
        }

        return $quantity;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected function createBaseCalculatedDiscountTransfer(DiscountTransfer $discountTransfer)
    {
        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->fromArray($discountTransfer->toArray(), true);

        return $calculatedDiscountTransfer;
    }
}
