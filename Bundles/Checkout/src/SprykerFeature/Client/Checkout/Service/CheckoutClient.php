<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\Checkout\Service\Zed\CheckoutStub;

/**
 * @method CheckoutDependencyContainer getDependencyContainer()
 */
class CheckoutClient extends AbstractClient implements CheckoutClientInterface
{
    /**
     * @param Order $order
     * @return \SprykerFeature\Shared\Library\Communication\Response
     */
    public function saveOrder(Order $order)
    {
        return $this->getZedStub()->requestCheckout($checkoutRequest);
    }


    /**
     * @return CheckoutStub
     */
    protected function getZedStub()
    {
        return $this->getDependencyContainer()->createZedStub();
    }
}
