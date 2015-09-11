<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Distributor;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;

interface DistributorInterface
{

    /**
     * @param array $discountableObjects
     * @param SpyDiscount $discountEntity
     * @param $amount
     */
    public function distribute(array $discountableObjects, SpyDiscount $discountEntity, $amount);

}
