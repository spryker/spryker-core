<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Distributor;

use Generated\Shared\Discount\DiscountInterface;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;

class Distributor implements DistributorInterface
{

    /**
     * @var float
     */
    protected $roundingError;

    /**
     * @param array $discountableObjects
     * @param DiscountInterface $discountTransfer
     */
    public function distribute(array $discountableObjects, DiscountInterface $discountTransfer)
    {
        $amount = $discountTransfer->getAmount();
        foreach ($discountableObjects as $discountableObject) {
            $totalGrossPrice = $this->getGrossPriceOfDiscountableObjects($discountableObjects);

            if ($totalGrossPrice === 0) {
                continue;
            }

            if ($amount <= 0) {
                continue;
            }

            /*
             * There should not be a discount that is higher than the total gross price of all discountable objects
             */
            if ($amount > $totalGrossPrice) {
                $amount = $totalGrossPrice;
            }

            $percentage = $discountableObject->getGrossPrice() / $totalGrossPrice;
            $discountAmount = $this->roundingError + $amount * $percentage;
            $discountAmountRounded = round($discountAmount, 2);
            $this->roundingError = $discountAmount - $discountAmountRounded;

            $distributedDiscountTransfer = clone $discountTransfer;
            $distributedDiscountTransfer->setAmount($discountAmountRounded);

            $this->addDiscountToDiscounts($discountableObject->getDiscounts(), $distributedDiscountTransfer);
        }
    }

    /**
     * @param \ArrayObject $discounts
     * @param DiscountInterface $discountTransfer
     */
    protected function addDiscountToDiscounts(\ArrayObject $discounts, DiscountInterface $discountTransfer)
    {
        $discounts->append($discountTransfer);
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     *
     * @return int
     */
    protected function getGrossPriceOfDiscountableObjects($discountableObjects)
    {
        $totalGrossPrice = 0;

        foreach ($discountableObjects as $discountableObject) {
            $totalGrossPrice += $discountableObject->getGrossPrice();
        }

        return $totalGrossPrice;
    }

}
