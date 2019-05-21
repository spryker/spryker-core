<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCartClientBridge;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToPersistentCartClientBridge;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientBridge;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToZedRequestClientBridge;

/**
 * @method \Spryker\Client\QuoteRequest\QuoteRequestConfig getConfig()
 */
class QuoteRequestDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_PERSISTENT_CART = 'CLIENT_PERSISTENT_CART';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const CLIENT_CART = 'CLIENT_CART';
    public const PLUGINS_QUOTE_REQUEST_CREATE_PRE_CHECK = 'PLUGINS_QUOTE_REQUEST_CREATE_PRE_CHECK';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addPersistentCartClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addQuoteRequestQuoteCheckPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return new QuoteRequestToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPersistentCartClient(Container $container): Container
    {
        $container[static::CLIENT_PERSISTENT_CART] = function (Container $container) {
            return new QuoteRequestToPersistentCartClientBridge($container->getLocator()->persistentCart()->client());
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
            return new QuoteRequestToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new QuoteRequestToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteRequestQuoteCheckPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_REQUEST_CREATE_PRE_CHECK] = function (Container $container) {
            return $this->getQuoteRequestQuoteCheckPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\QuoteRequestExtension\Dependency\Plugin\QuoteRequestQuoteCheckPluginInterface[]
     */
    protected function getQuoteRequestQuoteCheckPlugins(): array
    {
        return [];
    }
}
