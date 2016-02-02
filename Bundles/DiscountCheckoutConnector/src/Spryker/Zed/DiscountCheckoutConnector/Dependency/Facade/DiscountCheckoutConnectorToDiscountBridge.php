<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade;

use Spryker\Zed\Discount\Business\DiscountFacade;

class DiscountCheckoutConnectorToDiscountBridge implements DiscountCheckoutConnectorToDiscountInterface
{

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacade $discountFacade
     */
    public function __construct($discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param array $codes
     *
     * @return bool
     */
    public function useVoucherCodes(array $codes)
    {
        return $this->discountFacade->useVoucherCodes($codes);
    }

}
