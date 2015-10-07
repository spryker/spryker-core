<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneCheckoutConnector\Communication\Plugin;

use Generated\Shared\Checkout\OrderInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Zed\PayoneCheckoutConnector\Communication\PayoneCheckoutConnectorDependencyContainer;

/**
 * @method PayoneCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CheckoutPostSaveHookPlugin extends AbstractPlugin implements CheckoutPostSaveHookInterface
{

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function executeHook(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getDependencyContainer()->createPayoneFacade()->postSaveHook($orderTransfer, $checkoutResponse);
    }

}
