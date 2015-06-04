<?php

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

interface DiscountableOptionsInterface
{

    /**
     * @return DiscountItemInterface[]
     */
    public function getDiscounts();

    /**
     * @param \ArrayObject $collection
     */
    public function setDiscounts(\ArrayObject $collection);
}
