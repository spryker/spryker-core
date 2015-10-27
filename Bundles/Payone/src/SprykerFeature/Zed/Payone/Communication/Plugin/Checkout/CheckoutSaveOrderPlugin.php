<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Plugin\Checkout;

use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;
use SprykerFeature\Zed\Payone\Communication\PayoneDependencyContainer;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 * @method PayoneFacade getFacade()
 */
class CheckoutSaveOrderPlugin extends AbstractPlugin implements CheckoutSaveOrderInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->saveOrder($orderTransfer);
    }

}
