<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode;

use Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientBridge;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientBridge;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToZedRequestClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CartCodeDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_CALCULATION = 'CLIENT_CALCULATION';
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const PLUGIN_CART_CODE_COLLECTION = 'PLUGIN_CART_CODE_COLLECTION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        parent::provideServiceLayerDependencies($container);
        $container = $this->addCalculationClient($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addCartCodePluginCollection($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCalculationClient(Container $container): Container
    {
        $container->set(static::CLIENT_CALCULATION, function (Container $container) {
            return new CartCodeToCalculationClientBridge($container->getLocator()->calculation()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new CartCodeToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartCodePluginCollection(Container $container): Container
    {
        $container->set(static::PLUGIN_CART_CODE_COLLECTION, function () {
            return $this->getCartCodePluginCollection();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[]
     */
    protected function getCartCodePluginCollection(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new CartCodeToZedRequestClientBridge(
                $container->getLocator()->zedRequest()->client()
            );
        });

        return $container;
    }
}
