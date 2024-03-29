<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart;

use Spryker\Client\Cart\Dependency\Client\CartToMessengerClientBridge;
use Spryker\Client\Cart\Dependency\Client\CartToQuoteBridge;
use Spryker\Client\Cart\Dependency\Client\CartToUtilTextServiceBridge;
use Spryker\Client\Cart\Plugin\ItemCountPlugin;
use Spryker\Client\Cart\Plugin\SessionQuoteStorageStrategyPlugin;
use Spryker\Client\Cart\Plugin\SimpleProductQuoteItemFinderPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CartDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'quote client';

    /**
     * @var string
     */
    public const CLIENT_MESSENGER = 'CLIENT_MESSENGER';

    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'zed request client';

    /**
     * @var string
     */
    public const PLUGIN_ITEM_COUNT = 'item count plugin';

    /**
     * @var string
     */
    public const PLUGINS_QUOTE_STORAGE_STRATEGY = 'PLUGINS_QUOTE_STORAGE_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_ADD_ITEMS_REQUEST_EXPANDER = 'PLUGINS_ADD_ITEMS_REQUEST_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_REMOVE_ITEMS_REQUEST_EXPANDER = 'PLUGINS_REMOVE_ITEMS_REQUEST_EXPANDER';

    /**
     * @var string
     */
    public const PLUGIN_QUOTE_ITEM_FINDER = 'PLUGIN_QUOTE_ITEMS_FINDER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addQuoteClient($container);
        $container = $this->addMessengerClient($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addItemCountPlugin($container);
        $container = $this->addQuoteStorageStrategyPlugins($container);
        $container = $this->addQuoteItemFinderPlugin($container);
        $container = $this->addAddItemsRequestExpanderPlugins($container);
        $container = $this->addRemoveItemsRequestExpanderPlugins($container);
        $container = $this->addUtilTextService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteClient(Container $container)
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new CartToQuoteBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMessengerClient(Container $container): Container
    {
        $container->set(static::CLIENT_MESSENGER, function (Container $container) {
            return new CartToMessengerClientBridge($container->getLocator()->messenger()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container)
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addItemCountPlugin(Container $container)
    {
        $container->set(static::PLUGIN_ITEM_COUNT, function () {
            return $this->getItemCountPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteStorageStrategyPlugins(Container $container)
    {
        $container->set(static::PLUGINS_QUOTE_STORAGE_STRATEGY, function () {
            return $this->getQuoteStorageStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteItemFinderPlugin(Container $container)
    {
        $container->set(static::PLUGIN_QUOTE_ITEM_FINDER, function () {
            return $this->getQuoteItemFinderPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addAddItemsRequestExpanderPlugins(Container $container)
    {
        $container->set(static::PLUGINS_ADD_ITEMS_REQUEST_EXPANDER, function () {
            return $this->getAddItemsRequestExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addRemoveItemsRequestExpanderPlugins(Container $container)
    {
        $container->set(static::PLUGINS_REMOVE_ITEMS_REQUEST_EXPANDER, function () {
            return $this->getRemoveItemsRequestExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilTextService(Container $container)
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new CartToUtilTextServiceBridge(
                $container->getLocator()->utilText()->service(),
            );
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\Cart\Dependency\Plugin\ItemCountPluginInterface
     */
    protected function getItemCountPlugin()
    {
        return new ItemCountPlugin();
    }

    /**
     * @return array<\Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface>
     */
    protected function getQuoteStorageStrategyPlugins()
    {
        return [
            new SessionQuoteStorageStrategyPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\CartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected function getQuoteItemFinderPlugin()
    {
        return new SimpleProductQuoteItemFinderPlugin();
    }

    /**
     * @return array<\Spryker\Client\CartExtension\Dependency\Plugin\CartChangeRequestExpanderPluginInterface>
     */
    protected function getAddItemsRequestExpanderPlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Client\CartExtension\Dependency\Plugin\CartChangeRequestExpanderPluginInterface>
     */
    protected function getRemoveItemsRequestExpanderPlugins()
    {
        return [];
    }
}
