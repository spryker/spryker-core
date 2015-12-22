<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade;

use Orm\Zed\Discount\Persistence\SpyDiscount;

interface DiscountCheckoutConnectorToDiscountInterface
{

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes);

}
