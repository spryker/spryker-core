<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemInterface;

class Distributor implements
    DistributorInterface
{

    /**
     * @var float
     */
    protected $roundingError;

    /**
     * @var Locator
     */
    protected $locator;

    /**
     * @param Locator $locator
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param DiscountableItemInterface[] $discountableObjects
     * @param float $amount
     */
    public function distribute(array $discountableObjects, $amount)
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
            $this->addDiscountToDiscounts($discountableObject->getDiscounts(), $discountAmountRounded);
        }
    }

    /**
     * @param \ArrayObject $discounts
     * @param int $discountAmount
     */
    protected function addDiscountToDiscounts(\ArrayObject $discounts, $discountAmount)
    {
        $discount = new DiscountTransfer();
        $discount->setAmount($discountAmount);
        $discounts->append($discount);
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

    /**
     * @return Locator|AutoCompletion
     */
    protected function getLocator()
    {
        return $this->locator;
    }

}
