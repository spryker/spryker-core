<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\CustomerCheckoutConnector\Business\CustomerCheckoutConnectorFacade;
use Spryker\Zed\CustomerCheckoutConnector\Communication\CustomerCheckoutConnectorCommunicationFactory;

/**
 * @method CustomerCheckoutConnectorFacade getFacade()
 * @method CustomerCheckoutConnectorCommunicationFactory getFactory()
 */
class CustomerPreConditionCheckerPlugin extends AbstractPlugin implements CheckoutPreConditionInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->checkPreConditions($checkoutRequest, $checkoutResponse);
    }

}
