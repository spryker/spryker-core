<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CheckoutDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_ZED = 'zed service';
    public const PLUGINS_QUOTE_PROCEED_CHECKOUT_CHECK = 'PLUGINS_QUOTE_PROCEED_CHECKOUT_CHECK';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        $container = $this->addQuoteProceedCheckoutCheckPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteProceedCheckoutCheckPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_PROCEED_CHECKOUT_CHECK] = function () {
            return $this->getQuoteProceedCheckoutCheckPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Checkout\Plugin\QuoteProceedCheckoutCheckPluginInterface[]
     */
    protected function getQuoteProceedCheckoutCheckPlugins(): array
    {
        return [];
    }
}
