<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Distributor;

interface DistributorInterface
{

    /**
     * @param DiscountableItemInterface[] $discountableObjects
     * @param float $amount
     */
    public function distribute(array $discountableObjects, $amount);

}
