<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PersistentCart\Communication\Plugin\SimpleProductQuoteItemFinderPlugin;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeBridge;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeBridge;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeBridge;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToStoreFacadeBridge;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

/**
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 */
class PersistentCartDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CART = 'FACADE_CART';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_QUOTE = 'FACADE_QUOTE';
    public const FACADE_STORE = 'FACADE_STORE';
    public const PLUGIN_QUOTE_ITEM_FINDER = 'PLUGIN_QUOTE_ITEM_FINDER';
    public const PLUGINS_QUOTE_RESPONSE_EXPANDER = 'PLUGINS_QUOTE_RESPONSE_EXPANDER';
    public const PLUGINS_REMOVE_ITEMS_REQUEST_EXPANDER = 'PLUGINS_REMOVE_ITEMS_REQUEST_EXPANDER';
    public const PLUGINS_CART_ADD_ITEM_STRATEGY = 'PLUGINS_CART_ADD_ITEM_STRATEGY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCartFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addQuoteItemFinderPlugin($container);
        $container = $this->addQuoteFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addQuoteResponseExpanderPlugins($container);
        $container = $this->addRemoveItemsRequestExpanderPlugins($container);
        $container = $this->addCartAddItemStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addQuoteFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container): Container
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
    protected function addCartFacade(Container $container): Container
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
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new PersistentCartToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new PersistentCartToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteItemFinderPlugin(Container $container): Container
    {
        $container[static::PLUGIN_QUOTE_ITEM_FINDER] = function (Container $container) {
            return $this->getQuoteItemFinderPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteResponseExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_RESPONSE_EXPANDER] = function (Container $container) {
            return $this->getQuoteResponseExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRemoveItemsRequestExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_REMOVE_ITEMS_REQUEST_EXPANDER] = function (Container $container) {
            return $this->getRemoveItemsRequestExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartAddItemStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CART_ADD_ITEM_STRATEGY] = function (Container $container) {
            return $this->getCartAddItemStrategyPlugins($container);
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected function getQuoteItemFinderPlugin(): QuoteItemFinderPluginInterface
    {
        return new SimpleProductQuoteItemFinderPlugin();
    }

    /**
     * @return \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteResponseExpanderPluginInterface[]
     */
    protected function getQuoteResponseExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\CartChangeRequestExpandPluginInterface[]
     */
    protected function getRemoveItemsRequestExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface[]
     */
    protected function getCartAddItemStrategyPlugins(Container $container): array
    {
        return [];
    }
}
