<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Business;

use SprykerFeature\Zed\Checkout\Business\Workflow\CheckoutWorkflow;
use Generated\Zed\Ide\FactoryAutoCompletion\CheckoutBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Checkout\Business\Workflow\CheckoutWorkflowInterface;
use SprykerFeature\Zed\Checkout\CheckoutDependencyProvider;

class CheckoutDependencyContainer extends AbstractBusinessDependencyContainer
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
