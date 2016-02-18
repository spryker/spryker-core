<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Business\Calculation\CalculableContainer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreHydrationInterface;

/**
 * @method \Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig getConfig()
 * @method \Spryker\Zed\CalculationCheckoutConnector\Business\CalculationCheckoutConnectorFacade getFacade()
 * @method \Spryker\Zed\CalculationCheckoutConnector\Communication\CalculationCheckoutConnectorCommunicationFactory getFactory()
 */
class Recalculate extends AbstractPlugin implements CheckoutPreHydrationInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function execute(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $calculableCart = new CalculableContainer($checkoutRequest->getCart());
        $this->getFacade()->recalculate($calculableCart);
    }

}
