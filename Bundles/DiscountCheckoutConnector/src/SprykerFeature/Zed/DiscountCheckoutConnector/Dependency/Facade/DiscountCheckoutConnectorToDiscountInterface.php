<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Dependency\Facade;

use Orm\Zed\Discount\Persistence\SpyDiscount;

interface DiscountCheckoutConnectorToDiscountInterface
{

    /**
     * @param string $displayName
     *
     * @return SpyDiscount
     */
    public function getDiscountByDisplayName($displayName);

}
