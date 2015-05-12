<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use Generated\Shared\Transfer\CalculationDiscountTransfer;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountItemInterface;

interface DiscountableInterface
{
    /**
     * @param \ArrayObject $discounts
     *
     * @return $this
     */
    public function setDiscounts(\ArrayObject $discounts);

    /**
     * @return DiscountItemInterface[]|\ArrayObject
     */
    public function getDiscounts();

    /**
     * @param CalculationDiscountTransfer $discount
     *
     * @return $this
     */
    public function addDiscount(CalculationDiscountTransfer $discount);
}
