<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;

/**
 * @method \Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig getConfig()
 * @method \Spryker\Zed\CalculationCheckoutConnector\Business\CalculationCheckoutConnectorFacade getFacade()
 */
class CartAmountCorrectPrecondition implements CheckoutPreConditionInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->checkCartAmountCorrect($checkoutRequest, $checkoutResponse);
    }

}
