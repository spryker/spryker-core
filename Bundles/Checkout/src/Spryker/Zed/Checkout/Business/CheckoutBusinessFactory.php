<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Checkout\Business;

use Spryker\Zed\Checkout\Business\Workflow\CheckoutWorkflow;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Checkout\Business\Workflow\CheckoutWorkflowInterface;
use Spryker\Zed\Checkout\CheckoutDependencyProvider;
use Spryker\Zed\Checkout\CheckoutConfig;

/**
 * @method CheckoutConfig getConfig()
 */
class CheckoutBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CheckoutWorkflowInterface
     */
    public function createCheckoutWorkflow()
    {
        return new CheckoutWorkflow(
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_PRE_HYDRATOR),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_ORDER_HYDRATORS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_POST_HOOKS),
            $this->getProvidedDependency(CheckoutDependencyProvider::FACADE_OMS),
            $this->getProvidedDependency(CheckoutDependencyProvider::FACADE_CALCULATION)
        );
    }

}
