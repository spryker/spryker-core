<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Quote\Session\QuoteSession;
use Spryker\Client\Quote\StorageStrategy\DatabaseStorageStrategy;
use Spryker\Client\Quote\StorageStrategy\SessionStorageStrategy;
use Spryker\Client\Quote\StorageStrategy\StorageStrategyProvider;
use Spryker\Client\Quote\Zed\QuoteStub;

/**
 * @method \Spryker\Client\Quote\QuoteConfig getConfig()
 */
class QuoteFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Quote\Session\QuoteSessionInterface
     */
    public function createSession()
    {
        return new QuoteSession(
            $this->getSessionClient(),
            $this->getCurrencyPlugin(),
            $this->getQuoteTransferExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    public function getStorageStrategy()
    {
        return $this->createStorageStrategyProvider()
            ->provideStorage();
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyProviderInterface
     */
    protected function createStorageStrategyProvider()
    {
        return new StorageStrategyProvider(
            $this->getConfig(),
            $this->getStorageStrategyList()
        );
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface[]
     */
    protected function getStorageStrategyList()
    {
        return [
            $this->createSessionStorageStrategy(),
            $this->createDatabaseStorageStrategy(),
        ];
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    protected function createSessionStorageStrategy()
    {
        return new SessionStorageStrategy(
            $this->createSession()
        );
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    protected function createDatabaseStorageStrategy()
    {
        return new DatabaseStorageStrategy(
            $this->getCustomerClient(),
            $this->createZedQuoteStub(),
            $this->createSession()
        );
    }

    /**
     * @return \Spryker\Client\Quote\Zed\QuoteStubInterface
     */
    public function createZedQuoteStub()
    {
        return new QuoteStub($this->getZedService());
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\Currency\Plugin\CurrencyPluginInterface
     */
    protected function getCurrencyPlugin()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::CURRENCY_PLUGIN);
    }

    /**
     * @return \Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface[]
     */
    protected function getQuoteTransferExpanderPlugins()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::QUOTE_TRANSFER_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface
     */
    protected function getCustomerClient()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedService()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::SERVICE_ZED);
    }
}
