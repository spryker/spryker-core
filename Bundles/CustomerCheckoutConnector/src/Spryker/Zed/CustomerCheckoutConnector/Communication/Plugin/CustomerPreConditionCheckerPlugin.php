<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;

/**
 * @method \Spryker\Zed\CustomerCheckoutConnector\Business\CustomerCheckoutConnectorFacade getFacade()
 * @method \Spryker\Zed\CustomerCheckoutConnector\Communication\CustomerCheckoutConnectorCommunicationFactory getFactory()
 */
class CustomerPreConditionCheckerPlugin extends AbstractPlugin implements CheckoutPreConditionInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->checkPreConditions($checkoutRequest, $checkoutResponse);
    }

}
