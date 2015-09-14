<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CalculationCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Zed\CalculationCheckoutConnector\Business\CalculationCheckoutConnectorFacade;
use SprykerFeature\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig;
use SprykerFeature\Zed\Checkout\Business\Calculation\CalculableContainer;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreconditionInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreHydrationInterface;

/**
 * @method CalculationCheckoutConnectorConfig getConfig()
 * @method CalculationCheckoutConnectorFacade getFacade()
 */
class Recalculate implements CheckoutPreHydrationInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function execute(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $calculableCart = new CalculableContainer($checkoutRequest->getCart());
        $this->getFacade()->recalculate($calculableCart);
    }

}
