<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector\Dependency\Facade;

use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;

interface DiscountCheckoutConnectorToDiscountInterface
{

    /**
     * @param string $displayName
     *
     * @return SpyDiscount
     */
    public function getDiscountByDisplayName($displayName);

}
