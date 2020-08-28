<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Controller;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuoteAction(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        return $this->getFacade()->switchMerchantInQuote($merchantSwitchRequestTransfer);
    }
}
