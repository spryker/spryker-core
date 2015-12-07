<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CalculationCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Zed\CalculationCheckoutConnector\Business\CalculationCheckoutConnectorFacade;
use SprykerFeature\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;

/**
 * @method CalculationCheckoutConnectorConfig getConfig()
 * @method CalculationCheckoutConnectorFacade getFacade()
 */
class CartAmountCorrectPrecondition implements CheckoutPreConditionInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->checkCartAmountCorrect($checkoutRequest, $checkoutResponse);
    }

}
