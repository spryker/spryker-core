<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\OmsDiscountConnector\Dependency\Facade;

use Spryker\Zed\Discount\Business\DiscountFacade;

class OmsDiscountConnectorToDiscountBridge implements OmsDiscountConnectorToDiscountInterface
{

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @param DiscountFacade $discountFacade
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
