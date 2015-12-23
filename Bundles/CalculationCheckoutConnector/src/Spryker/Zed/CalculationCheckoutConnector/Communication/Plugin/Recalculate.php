<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\CalculationCheckoutConnector\Business\CalculationCheckoutConnectorFacade;
use Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig;
use Spryker\Zed\Checkout\Business\Calculation\CalculableContainer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreHydrationInterface;
use Spryker\Zed\CalculationCheckoutConnector\Communication\CalculationCheckoutConnectorCommunicationFactory;

/**
 * @method CalculationCheckoutConnectorConfig getConfig()
 * @method CalculationCheckoutConnectorFacade getFacade()
 * @method CalculationCheckoutConnectorCommunicationFactory getFactory()
 */
class Recalculate extends AbstractPlugin implements CheckoutPreHydrationInterface
{

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function execute(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $calculableCart = new CalculableContainer($checkoutRequest->getCart());
        $this->getFacade()->recalculate($calculableCart);
    }

}
