<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout;

use Spryker\Client\Checkout\Quote\QuoteProceedCheckoutChecker;
use Spryker\Client\Checkout\Quote\QuoteProceedCheckoutCheckerInterface;
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
     * @return \Spryker\Client\Checkout\Quote\QuoteProceedCheckoutCheckerInterface
     */
    public function createQuoteProceedCheckoutChecker(): QuoteProceedCheckoutCheckerInterface
    {
        return new QuoteProceedCheckoutChecker($this->getCheckoutPreCheckPlugins());
    }

    /**
     * @return \Spryker\Client\CheckoutExtension\Dependency\Plugin\CheckoutPreCheckPluginInterface[]
     */
    public function getCheckoutPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::PLUGINS_CHECKOUT_PRE_CHECK);
    }
}
