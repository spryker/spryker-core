<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Communication\Controller;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Checkout\Business\CheckoutFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function requestCheckoutAction(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        $result = $this->getFacade()->requestCheckout($checkoutRequestTransfer);

        return $result;
    }

}
