<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\OmsDiscountConnector\Dependency\Facade;

class OmsDiscountConnectorToDiscountBridge implements OmsDiscountConnectorToDiscountInterface
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
    public function releaseUsedVoucherCodes(array $codes)
    {
        return $this->discountFacade->releaseUsedVoucherCodes($codes);
    }

}
