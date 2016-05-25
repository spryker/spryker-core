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
use Spryker\Zed\Discount\Business\Exception\DistributorException;

class Distributor implements DistributorInterface
{

    /**
     * @var float
     */
    protected $roundingError = 0.0;

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     * @throws \Spryker\Zed\Discount\Business\Exception\DistributorException
     *
     * @return void
     */
    public function distribute(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        $totalGrossAmount = $this->getTotalGrossAmountOfDiscountableObjects($collectedDiscountTransfer);
        if ($totalGrossAmount <= 0) {
            return;
        }

        $totalDiscountAmount = $collectedDiscountTransfer->getDiscount()->getAmount();
        if ($totalDiscountAmount <= 0) {
            return;
        }

        // There should not be a discount that is higher than the total gross price of all discountable objects
        if ($totalDiscountAmount > $totalGrossAmount) {
            $totalDiscountAmount = $totalGrossAmount;
        }

        $calculatedDiscountTransfer = $this->createBaseCalculatedDiscountTransfer($collectedDiscountTransfer->getDiscount());

        foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItemTransfer) {
            if (!$this->isOriginalItemCalculatedDiscountsProvided($discountableItemTransfer)) {
                continue;
            }
            $singleItemGrossAmountShare = $discountableItemTransfer->getUnitGrossPrice() / $totalGrossAmount;

            $itemDiscountAmount = ($totalDiscountAmount * $singleItemGrossAmountShare) + $this->roundingError;
            $itemDiscountAmountRounded = round($itemDiscountAmount, 2);
            $this->roundingError = $itemDiscountAmount - $itemDiscountAmountRounded;

            $distributedDiscountTransfer = clone $calculatedDiscountTransfer;
            $distributedDiscountTransfer->setUnitGrossAmount($itemDiscountAmountRounded);
            $distributedDiscountTransfer->setQuantity($discountableItemTransfer->getQuantity());

            $discountableItemTransfer->getOriginalItemCalculatedDiscounts()->append($distributedDiscountTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return bool
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\DistributorException
     */
    protected function isOriginalItemCalculatedDiscountsProvided(DiscountableItemTransfer $discountableItemTransfer)
    {
        if ($discountableItemTransfer->getOriginalItemCalculatedDiscounts() === false) {
            throw new DistributorException(
                'Original item calculated discounts object reference must be provided to DiscountableItemTransfer.'
            );
        }

        if (!$discountableItemTransfer->getOriginalItemCalculatedDiscounts() instanceof \ArrayObject) {
            throw new DistributorException(
                'Original item calculated discounts object must be instance of \ArrayObject.'
            );
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return int
     */
    protected function getTotalGrossAmountOfDiscountableObjects(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        $totalGrossAmount = 0;
        foreach ($collectedDiscountTransfer->getDiscountableItems() as $discountableItemTransfer) {
            $totalGrossAmount += $discountableItemTransfer->getUnitGrossPrice() *
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
