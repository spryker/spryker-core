<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

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
     * @param DiscountItemInterface $discount
     *
     * @return $this
     */
    public function addDiscount(DiscountItemInterface $discount);

    /**
     * @param DiscountItemInterface $discount
     *
     * @return $this
     */
    public function removeDiscount(DiscountItemInterface $discount);
}
