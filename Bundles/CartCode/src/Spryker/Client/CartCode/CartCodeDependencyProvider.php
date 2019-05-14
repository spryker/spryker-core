<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode;

use Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientBridge;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CartCodeDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_CALCULATION = 'CLIENT_CALCULATION';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const PLUGIN_CART_CODE_HANDLER_COLLECTION = 'PLUGIN_CART_CODE_HANDLER_COLLECTION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addCalculationClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addCartCodeHandlerPluginCollection($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCalculationClient(Container $container): Container
    {
        $container[static::CLIENT_CALCULATION] = function (Container $container) {
            return new CartCodeToCalculationClientBridge($container->getLocator()->calculation()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new CartCodeToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartCodeHandlerPluginCollection(Container $container): Container
    {
        $container[static::PLUGIN_CART_CODE_HANDLER_COLLECTION] = function () {
            return $this->getCartCodeHandlerPluginCollection();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getCartCodeHandlerPluginCollection()
    {
        return [];
    }
}
