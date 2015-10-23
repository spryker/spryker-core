<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Distributor;

use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

class Distributor implements DistributorInterface
{
    /**
     * @var float
     */
    protected $roundingError = 0;

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param DiscountInterface       $discountTransfer
     *
     * @return void
     */
    public function distribute(array $discountableObjects, DiscountInterface $discountTransfer)
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
     * @param DiscountableInterface[] $discountableObjects
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
     * @param DiscountableInterface $discountableItemTransfer
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
