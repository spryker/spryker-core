<?php

namespace SprykerFeature\Sdk\Checkout;

use Generated\Sdk\Ide\FactoryAutoCompletion\Checkout;
use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;
use SprykerFeature\Sdk\Checkout\Model\CheckoutManager;

class CheckoutDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var CheckoutManager
     */
    protected $checkoutManager;

    /**
     * @var Checkout
     */
    protected $factory;

    /**
     * @return CheckoutManager
     */
    public function createCheckoutManager()
    {
        if (!$this->checkoutManager) {
            $this->checkoutManager = $this->getFactory()->createModelCheckoutManager(
                $this->getLocator()->zedRequest()->zedClient()->getInstance()
            );
        }

        return $this->checkoutManager;
    }
}
