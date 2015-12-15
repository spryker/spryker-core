<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Distributor;

use Generated\Shared\Transfer\DiscountTransfer;

interface DistributorInterface
{

    /**
     * @param array $discountableObjects
     * @param DiscountTransfer $discountTransfer
     */
    public function distribute(array $discountableObjects, DiscountTransfer $discountTransfer);

}
