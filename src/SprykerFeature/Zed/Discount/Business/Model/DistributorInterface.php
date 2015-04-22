<?php

namespace SprykerFeature\Zed\Discount\Business\Model;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemInterface;

interface DistributorInterface
{
    /**
     * @param DiscountableItemInterface[] $discountableObjects
     * @param float $amount
     */
    public function distribute(array $discountableObjects, $amount);
}
