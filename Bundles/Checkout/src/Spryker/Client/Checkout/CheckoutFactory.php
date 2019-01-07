<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout;

use Spryker\Client\Checkout\PluginExecutor\QuoteProceedCheckoutCheckPluginExecutor;
use Spryker\Client\Checkout\PluginExecutor\QuoteProceedCheckoutCheckPluginExecutorInterface;
use Spryker\Client\Checkout\Zed\CheckoutStub;
use Spryker\Client\Kernel\AbstractFactory;

class CheckoutFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Checkout\Zed\CheckoutStubInterface
     */
    public function createZedStub()
    {
        return new CheckoutStub($this->getProvidedDependency(CheckoutDependencyProvider::SERVICE_ZED));
    }

    /**
     * @return \Spryker\Client\Checkout\PluginExecutor\QuoteProceedCheckoutCheckPluginExecutorInterface
     */
    public function createProccedCheckoutPluginExecutor(): QuoteProceedCheckoutCheckPluginExecutorInterface
    {
        return new QuoteProceedCheckoutCheckPluginExecutor($this->getQuoteProceedCheckoutCheckPlugins());
    }

    /**
     * @return \Spryker\Client\Checkout\Plugin\QuoteProceedCheckoutCheckPluginInterface[]
     */
    protected function getQuoteProceedCheckoutCheckPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PLUGINS_QUOTE_PROCEED_CHECKOUT_CHECK);
    }
}
