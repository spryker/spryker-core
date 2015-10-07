<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneCheckoutConnector\Communication\Plugin;

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
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function executeHook(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getDependencyContainer()->createPayoneFacade()->postSaveHook($orderTransfer, $checkoutResponse);

        $checkoutResponse->setIsExternalRedirect(true);
        $checkoutResponse->setRedirectUrl('http://web.de');
    }

}
