<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Distributor;

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
     * @param SpyDiscount $discountEntity
     * @param int $amount
     */
    public function distribute(array $discountableObjects, SpyDiscount $discountEntity, $amount)
    {
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
            $this->addDiscountToDiscounts($discountableObject->getDiscounts(), $discountEntity, $discountAmountRounded);
        }
    }

    /**
     * @param \ArrayObject $discounts
     * @param SpyDiscount $discountEntity
     * @param int $discountAmount
     */
    protected function addDiscountToDiscounts(\ArrayObject $discounts, SpyDiscount $discountEntity, $discountAmount)
    {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->fromArray($discountEntity->toArray(), true);
        $discountTransfer->setAmount($discountAmount);
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
