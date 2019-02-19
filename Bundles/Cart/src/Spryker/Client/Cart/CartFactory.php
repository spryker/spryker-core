<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart;

use Spryker\Client\Cart\CartChangeRequestExpander\CartChangeRequestExpander;
use Spryker\Client\Cart\Dependency\Client\CartToMessengerClientInterface;
use Spryker\Client\Cart\QuoteStorageStrategy\QuoteStorageStrategyProvider;
use Spryker\Client\Cart\QuoteStorageStrategy\QuoteStorageStrategyProxy;
use Spryker\Client\Cart\QuoteStorageStrategy\QuoteStorageStrategyProxyInterface;
use Spryker\Client\Cart\Zed\CartStub;
use Spryker\Client\Kernel\AbstractFactory;

class CartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\Cart\Dependency\Client\CartToMessengerClientInterface
     */
    public function getMessengerClient(): CartToMessengerClientInterface
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_MESSENGER);
    }

    /**
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
     */
    public function createZedStub()
    {
        return new CartStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\Cart\Dependency\Plugin\ItemCountPluginInterface
     */
    public function getItemCounter()
    {
        return $this->getProvidedDependency(CartDependencyProvider::PLUGIN_ITEM_COUNT);
    }

    /**
     * @return \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface
     */
    public function getQuoteStorageStrategy()
    {
        return $this->createQuoteStorageStrategyProvider()->provideStorage();
    }

    /**
     * @return \Spryker\Client\Cart\QuoteStorageStrategy\QuoteStorageStrategyProxyInterface
     */
    public function createQuoteStorageStrategyProxy(): QuoteStorageStrategyProxyInterface
    {
        return new QuoteStorageStrategyProxy(
            $this->getMessengerClient(),
            $this->getQuoteClient(),
            $this->getQuoteStorageStrategy()
        );
    }

    /**
     * @return \Spryker\Client\Cart\QuoteStorageStrategy\QuoteStorageStrategyProviderInterface
     */
    public function createQuoteStorageStrategyProvider()
    {
        return new QuoteStorageStrategyProvider(
            $this->getQuoteClient(),
            $this->getQuoteStorageStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Client\Cart\CartChangeRequestExpander\CartChangeRequestExpanderInterface
     */
    public function createCartChangeRequestExpander()
    {
        return new CartChangeRequestExpander(
            $this->getAddItemsRequestExpanderPlugins(),
            $this->getRemoveItemsRequestExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface[]
     */
    protected function getQuoteStorageStrategyPlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::PLUGINS_QUOTE_STORAGE_STRATEGY);
    }

    /**
     * @return \Spryker\Client\CartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    public function getQuoteItemFinderPlugin()
    {
        return $this->getProvidedDependency(CartDependencyProvider::PLUGIN_QUOTE_ITEM_FINDER);
    }

    /**
     * @return \Spryker\Client\CartExtension\Dependency\Plugin\CartChangeRequestExpanderPluginInterface[]
     */
    protected function getAddItemsRequestExpanderPlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::PLUGINS_ADD_ITEMS_REQUEST_EXPANDER);
    }

    /**
     * @return \Spryker\Client\CartExtension\Dependency\Plugin\CartChangeRequestExpanderPluginInterface[]
     */
    protected function getRemoveItemsRequestExpanderPlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::PLUGINS_REMOVE_ITEMS_REQUEST_EXPANDER);
    }
}
