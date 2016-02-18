<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Distributor;

use Generated\Shared\Transfer\DiscountTransfer;

class Distributor implements DistributorInterface
{

    /**
     * @var float
     */
    protected $roundingError = 0;

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return void
     */
    public function distribute(array $discountableObjects, DiscountTransfer $discountTransfer)
    {
        $totalGrossAmount = $this->getTotalGrossAmountOfDiscountableObjects($discountableObjects);
        if ($totalGrossAmount <= 0) {
            return;
        }

        $totalDiscountAmount = $discountTransfer->getAmount();
        if ($totalDiscountAmount <= 0) {
            return;
        }

        /*
         * There should not be a discount that is higher than the total gross price of all discountable objects
         */
        if ($totalDiscountAmount > $totalGrossAmount) {
            $totalDiscountAmount = $totalGrossAmount;
        }

        foreach ($discountableObjects as $discountableItemTransfer) {
            $singleItemGrossAmountShare = $discountableItemTransfer->getGrossPrice()  / $totalGrossAmount;

            $itemDiscountAmount = ($totalDiscountAmount * $singleItemGrossAmountShare) + $this->roundingError;
            $itemDiscountAmountRounded = round($itemDiscountAmount, 2);
            $this->roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

            $distributedDiscountTransfer = clone $discountTransfer;
            $distributedDiscountTransfer->setAmount($itemDiscountAmountRounded);

            $discountableItemTransfer->getDiscounts()->append($distributedDiscountTransfer);
        }
    }

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     *
     * @return int
     */
    protected function getTotalGrossAmountOfDiscountableObjects($discountableObjects)
    {
        $totalGrossAmount = 0;
        foreach ($discountableObjects as $discountableItemTransfer) {
            $totalGrossAmount += $discountableItemTransfer->getGrossPrice() *
                $this->getDiscountableItemQuantity($discountableItemTransfer);
        }

        return $totalGrossAmount;
    }

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface $discountableItemTransfer
     *
     * @return int
     */
    protected function getDiscountableItemQuantity($discountableItemTransfer)
    {
        $quantity = 1;
        if (!empty($discountableItemTransfer->getQuantity())) {
            $quantity = $discountableItemTransfer->getQuantity();
        }

        return $quantity;
    }

}
