<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart;

use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationBridge;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CartDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_CALCULATION = 'calculation facade';
    const FACADE_MESSENGER = 'messenger facade';

    const CART_EXPANDER_PLUGINS = 'cart expander plugins';
    const CART_PRE_CHECK_PLUGINS = 'pre check plugins';
    const CART_REMOVAL_PRE_CHECK_PLUGINS = 'CART_REMOVAL_PRE_CHECK_PLUGINS';
    const CART_POST_SAVE_PLUGINS = 'cart post save plugins';
    const CART_PRE_RELOAD_PLUGINS = 'cart pre reload plugins';
    const CART_TERMINATION_PLUGINS = 'CART_TERMINATION_PLUGINS';
    const PLUGINS_QUOTE_CHANGE_OBSERVER = 'PLUGINS_QUOTE_CHANGE_OBSERVER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCalculationFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addExpanderPlugins($container);
        $container = $this->addPostSavePlugins($container);
        $container = $this->addPreCheckPlugins($container);
        $container = $this->addCartRemovalPreCheckPlugins($container);
        $container = $this->addPreReloadPlugins($container);
        $container = $this->addTerminationPlugins($container);
        $container = $this->addQuoteChangeObserverPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container)
    {
        $container[static::FACADE_CALCULATION] = function (Container $container) {
            return new CartToCalculationBridge($container->getLocator()->calculation()->facade());
        };
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container)
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new CartToMessengerBridge($container->getLocator()->messenger()->facade());
        };
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addExpanderPlugins(Container $container)
    {
        $container[static::CART_EXPANDER_PLUGINS] = function (Container $container) {
            return $this->getExpanderPlugins($container);
        };
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostSavePlugins(Container $container)
    {
        $container[static::CART_POST_SAVE_PLUGINS] = function (Container $container) {
            return $this->getPostSavePlugins($container);
        };
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreCheckPlugins(Container $container)
    {
        $container[static::CART_PRE_CHECK_PLUGINS] = function (Container $container) {
            return $this->getCartPreCheckPlugins($container);
        };
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartRemovalPreCheckPlugins(Container $container)
    {
        $container[static::CART_REMOVAL_PRE_CHECK_PLUGINS] = function (Container $container) {
            return $this->getCartRemovalPreCheckPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreReloadPlugins(Container $container)
    {
        $container[static::CART_PRE_RELOAD_PLUGINS] = function (Container $container) {
            return $this->getPreReloadPlugins($container);
        };
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTerminationPlugins(Container $container)
    {
        $container[static::CART_TERMINATION_PLUGINS] = function (Container $container) {
            return $this->getTerminationPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteChangeObserverPlugins(Container $container)
    {
        $container[static::PLUGINS_QUOTE_CHANGE_OBSERVER] = function (Container $container) {
            return $this->getQuoteChangeObserverPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[]
     */
    protected function getExpanderPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Cart\Dependency\PostSavePluginInterface[]
     */
    protected function getPostSavePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Cart\Dependency\CartPreCheckPluginInterface[]
     */
    protected function getCartPreCheckPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartRemovalPreCheckPluginInterface[]
     */
    protected function getCartRemovalPreCheckPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Cart\Dependency\PreReloadItemsPluginInterface[]
     */
    protected function getPreReloadPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface[]
     */
    protected function getTerminationPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\QuoteChangeObserverPluginInterface[]
     */
    protected function getQuoteChangeObserverPlugins(Container $container): array
    {
        return [];
    }
}
