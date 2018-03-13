<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart;

use Spryker\Client\Cart\QuoteStorageStrategy\QuoteStorageStrategyProvider;
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
     * @return \Spryker\Client\Cart\Zed\CartStubInterface
     */
    public function createZedStub()
    {
        return new CartStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
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
     * @return \Spryker\Client\Cart\Dependency\Plugin\QuoteStorageStrategyPluginInterface
     */
    public function getQuoteStorageStrategy()
    {
        return $this->createQuoteStorageStrategyProvider()->provideStorage();
    }

    /**
     * @return \Spryker\Client\Cart\QuoteStorageStrategy\QuoteStorageStrategyProviderInterface
     */
    protected function createQuoteStorageStrategyProvider()
    {
        return new QuoteStorageStrategyProvider(
            $this->getQuoteClient(),
            $this->getQuoteStorageStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Client\Cart\Dependency\Plugin\QuoteStorageStrategyPluginInterface[]
     */
    protected function getQuoteStorageStrategyPlugins()
    {
        return $this->getProvidedDependency(CartDependencyProvider::PLUGINS_QUOTE_STORAGE_STRATEGY);
    }
}
