<?php

namespace SprykerFeature\Client\Checkout;

use Generated\Client\Ide\FactoryAutoCompletion\Checkout;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Checkout\Model\CheckoutManager;

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
