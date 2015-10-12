<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneCheckoutConnector\Communication\Plugin;

use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Checkout\OrderInterface;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\PayoneCheckoutConnector\Communication\PayoneCheckoutConnectorDependencyContainer;

/**
 * @method PayoneCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CheckoutPostSaveHookPlugin extends AbstractPlugin implements CheckoutPostSaveHookInterface
{

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseInterface $checkoutResponse
     */
    public function executeHook(OrderInterface $orderTransfer, CheckoutResponseInterface $checkoutResponse)
    {
        $this->getDependencyContainer()->createPayoneFacade()->postSaveHook($orderTransfer, $checkoutResponse);
    }

}
