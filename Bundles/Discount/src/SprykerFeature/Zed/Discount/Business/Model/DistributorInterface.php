<?php

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\Discount\DependencyDiscountableItemInterfaceTransfer;

interface DistributorInterface
{
    /**
     * @param DiscountableItemInterface[] $discountableObjects
     * @param float $amount
     */
    public function distribute(array $discountableObjects, $amount);
}
