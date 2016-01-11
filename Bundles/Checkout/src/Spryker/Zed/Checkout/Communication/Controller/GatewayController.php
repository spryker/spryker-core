<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Checkout\Communication\Controller;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Business\CheckoutFacade;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method CheckoutFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return CheckoutResponseTransfer
     */
    public function placeOrderAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->placeOrder($quoteTransfer);
    }

}
