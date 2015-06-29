<?php

namespace SprykerFeature\Client\Checkout\Service;

use Generated\Client\Ide\FactoryAutoCompletion\Checkout;
use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;
use SprykerFeature\Client\Checkout\Service\Model\CheckoutManager;

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
