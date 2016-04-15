<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Distributor;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;

class Distributor implements DistributorInterface
{

    /**
     * @var float
     */
    protected $roundingError = 0.0;

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

        // There should not be a discount that is higher than the total gross price of all discountable objects
        if ($totalDiscountAmount > $totalGrossAmount) {
            $totalDiscountAmount = $totalGrossAmount;
        }

        $calculatedDiscountTransfer = $this->createBaseCalculatedDiscountTransfer($discountTransfer);

        foreach ($discountableObjects as $discountableItemTransfer) {
            $singleItemGrossAmountShare = $discountableItemTransfer->getUnitGrossPrice() / $totalGrossAmount;

            $itemDiscountAmount = ($totalDiscountAmount * $singleItemGrossAmountShare) + $this->roundingError;
            $itemDiscountAmountRounded = round($itemDiscountAmount, 2);
            $this->roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

            $distributedDiscountTransfer = clone $calculatedDiscountTransfer;
            $distributedDiscountTransfer->setUnitGrossAmount($itemDiscountAmountRounded);
            $distributedDiscountTransfer->setQuantity($discountableItemTransfer->getQuantity());

            $discountableItemTransfer->getCalculatedDiscounts()->append($distributedDiscountTransfer);
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
            $totalGrossAmount += $discountableItemTransfer->getUnitGrossPrice() *
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
