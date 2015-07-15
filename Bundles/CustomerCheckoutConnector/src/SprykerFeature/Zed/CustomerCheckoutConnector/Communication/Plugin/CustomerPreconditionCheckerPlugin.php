<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreconditionInterface;
use SprykerFeature\Zed\CustomerCheckoutConnector\Business\CustomerCheckoutConnectorFacade;

/**
 * @method CustomerCheckoutConnectorFacade getFacade()
 */
class CustomerPreconditionCheckerPlugin extends AbstractPlugin implements CheckoutPreconditionInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->checkPreconditions($checkoutRequest, $checkoutResponse);
    }

}
