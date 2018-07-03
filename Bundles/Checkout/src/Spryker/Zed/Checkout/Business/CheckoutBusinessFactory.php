<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business;

use Spryker\Zed\Checkout\Business\Workflow\CheckoutWorkflow;
use Spryker\Zed\Checkout\CheckoutDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Checkout\CheckoutConfig getConfig()
 */
class CheckoutBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Checkout\Business\Workflow\CheckoutWorkflowInterface
     */
    public function createCheckoutWorkflow()
    {
        return new CheckoutWorkflow(
            $this->getOmsFacade(),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_POST_HOOKS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS)
        );
    }

    /**
     * @return \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::FACADE_OMS);
    }
}
