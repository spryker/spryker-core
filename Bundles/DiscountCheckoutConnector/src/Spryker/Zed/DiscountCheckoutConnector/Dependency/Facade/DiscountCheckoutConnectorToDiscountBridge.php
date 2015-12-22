<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\DiscountCheckoutConnector\Dependency\Facade;

class DiscountCheckoutConnectorToDiscountBridge implements DiscountCheckoutConnectorToDiscountInterface
{

    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacade
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
