<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsDiscountConnector\Dependency\Facade;

class OmsDiscountConnectorToDiscountBridge implements OmsDiscountConnectorToDiscountInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacadeInterface $discountFacade
     */
    public function __construct($discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param string[] $codes
     *
     * @return int
     */
    public function releaseUsedVoucherCodes(array $codes)
    {
        return $this->discountFacade->releaseUsedVoucherCodes($codes);
    }
}
