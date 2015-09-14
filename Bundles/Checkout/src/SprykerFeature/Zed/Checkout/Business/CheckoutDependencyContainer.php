<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CheckoutBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Checkout\Business\Workflow\CheckoutWorkflowInterface;
use SprykerFeature\Zed\Checkout\CheckoutDependencyProvider;

/**
 * @method CheckoutBusiness getFactory()
 */
class CheckoutDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CheckoutWorkflowInterface
     */
    public function createCheckoutWorkflow()
    {
        return $this->getFactory()->createWorkflowCheckoutWorkflow(
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_PRECONDITIONS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_PRE_HYDRATOR),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_ORDERHYDRATORS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_ORDERSAVERS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_POSTHOOKS),
            $this->getProvidedDependency(CheckoutDependencyProvider::FACADE_OMS),
            $this->getProvidedDependency(CheckoutDependencyProvider::FACADE_CALCULATION)
        );
    }

}
