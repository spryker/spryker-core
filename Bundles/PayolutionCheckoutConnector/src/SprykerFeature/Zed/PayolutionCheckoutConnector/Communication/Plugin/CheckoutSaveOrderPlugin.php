<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayolutionCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;
use SprykerFeature\Zed\PayolutionCheckoutConnector\Communication\PayolutionCheckoutConnectorDependencyContainer;

/**
 * @method PayolutionCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CheckoutSaveOrderPlugin extends AbstractPlugin implements CheckoutSaveOrderInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getDependencyContainer()->createPayolutionFacade()->saveOrder($orderTransfer);
    }

}
