<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\CalculationCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\CalculationCheckoutConnector\Business\CalculationCheckoutConnectorFacade;
use SprykerFeature\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreconditionInterface;

/**
 * @method CalculationCheckoutConnectorConfig getConfig()
 * @method CalculationCheckoutConnectorFacade getFacade()
 */
class CartAmountCorrectPrecondition implements CheckoutPreconditionInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->checkCartAmountCorrect($checkoutRequest, $checkoutResponse);
    }

}
