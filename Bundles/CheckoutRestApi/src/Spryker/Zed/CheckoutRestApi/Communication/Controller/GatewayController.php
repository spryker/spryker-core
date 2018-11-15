<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Communication\Controller;

use Generated\Shared\Transfer\CheckoutDataResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutDataResponseTransfer
     */
    public function getCheckoutDataAction(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): CheckoutDataResponseTransfer
    {
        return $this->getFacade()->getCheckoutData($restCheckoutRequestAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrderAction(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): CheckoutResponseTransfer
    {
        return $this->getFacade()->placeOrder($restCheckoutRequestAttributesTransfer);
    }
}
