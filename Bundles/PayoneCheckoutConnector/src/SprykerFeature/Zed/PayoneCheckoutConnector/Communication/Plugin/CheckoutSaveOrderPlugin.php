<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneCheckoutConnector\Communication\Plugin;

use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class CheckoutSaveOrderPlugin extends AbstractPlugin implements CheckoutSaveOrderInterface{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getDependencyContainer()->createPayoneFacade()->saveOrder($orderTransfer);
    }

}
