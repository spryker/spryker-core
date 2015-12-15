<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\CalculationCheckoutConnector\Business\CalculationCheckoutConnectorFacade;
use Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;

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
