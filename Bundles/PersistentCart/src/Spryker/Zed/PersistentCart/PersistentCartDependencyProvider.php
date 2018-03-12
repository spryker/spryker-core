<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeBridge;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeBridge;

class PersistentCartDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_CART = 'FACADE_CART';
    const FACADE_QUOTE = 'FACADE_QUOTE';
    const PLUGINS_QUOTE_RESPONSE_EXPANDER = 'PLUGINS_QUOTE_RESPONSE_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCartFacade($container);
        $container = $this->addQuoteFacade($container);
        $container = $this->addQuoteResponseExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container)
    {
        $container[static::FACADE_QUOTE] = function (Container $container) {
            return new PersistentCartToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container)
    {
        $container[static::FACADE_CART] = function (Container $container) {
            return new PersistentCartToCartFacadeBridge($container->getLocator()->cart()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteResponseExpanderPlugins(Container $container)
    {
        $container[static::PLUGINS_QUOTE_RESPONSE_EXPANDER] = function (Container $container) {
            return $this->getQuoteResponseExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\PersistentCart\Dependency\Plugin\QuoteResponseExpanderPluginInterface[]
     */
    protected function getQuoteResponseExpanderPlugins()
    {
        return [];
    }
}
