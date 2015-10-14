<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Plugin\Checkout;

use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;
use SprykerFeature\Zed\Payone\Communication\PayoneDependencyContainer;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 * @method PayoneFacade getFacade()
 */
class CheckoutPostSaveHookPlugin extends AbstractPlugin implements CheckoutPostSaveHookInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseInterface $checkoutResponse
     */
    public function executeHook(OrderTransfer $orderTransfer, CheckoutResponseInterface $checkoutResponse)
    {
        $this->getFacade()->postSaveHook($orderTransfer, $checkoutResponse);
    }

}
