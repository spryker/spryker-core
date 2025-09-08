<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface;
use Spryker\Client\Quote\Dependency\Client\QuoteToStoreClientInterface;
use Spryker\Client\Quote\QuoteLocker\QuoteLocker;
use Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface;
use Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidator;
use Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface;
use Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidator;
use Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface;
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
            $this->getCurrencyClient(),
            $this->getStoreClient(),
            $this->getQuoteTransferExpanderPlugins(),
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
     * @return \Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface
     */
    public function createQuoteLocker(): QuoteLockerInterface
    {
        return new QuoteLocker();
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyProviderInterface
     */
    public function createStorageStrategyProvider()
    {
        return new StorageStrategyProvider(
            $this->getConfig(),
            $this->getStorageStrategyList(),
        );
    }

    /**
     * @return array<\Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface>
     */
    public function getStorageStrategyList()
    {
        return [
            $this->createSessionStorageStrategy(),
            $this->createDatabaseStorageStrategy(),
        ];
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    public function createSessionStorageStrategy()
    {
        return new SessionStorageStrategy(
            $this->createSession(),
            $this->createQuoteLockStatusValidator(),
            $this->createQuoteEditStatusValidator(),
            $this->createQuoteLocker(),
        );
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    public function createDatabaseStorageStrategy()
    {
        return new DatabaseStorageStrategy(
            $this->getCustomerClient(),
            $this->createZedQuoteStub(),
            $this->createSession(),
            $this->createQuoteLockStatusValidator(),
            $this->createQuoteEditStatusValidator(),
            $this->createQuoteLocker(),
            $this->getDatabaseStrategyPreCheckPlugins(),
            $this->getDatabaseStrategyReaderPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface
     */
    public function createQuoteEditStatusValidator(): QuoteEditStatusValidatorInterface
    {
        return new QuoteEditStatusValidator(
            $this->createQuoteLockStatusValidator(),
        );
    }

    /**
     * @return \Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface
     */
    public function createQuoteLockStatusValidator(): QuoteLockStatusValidatorInterface
    {
        return new QuoteLockStatusValidator();
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
    public function getSessionClient()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return array<\Spryker\Client\QuoteExtension\Dependency\Plugin\QuoteTransferExpanderPluginInterface>
     */
    public function getQuoteTransferExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::QUOTE_TRANSFER_EXPANDER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Client\QuoteExtension\Dependency\Plugin\DatabaseStrategyPreCheckPluginInterface>
     */
    public function getDatabaseStrategyPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_DATABASE_STRATEGY_PRE_CHECK_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedService()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::SERVICE_ZED);
    }

    /**
     * @return \Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface
     */
    public function getCurrencyClient(): QuoteToCurrencyClientInterface
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \Spryker\Client\Quote\Dependency\Client\QuoteToStoreClientInterface
     */
    public function getStoreClient(): QuoteToStoreClientInterface
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return array<\Spryker\Client\QuoteExtension\Dependency\Plugin\DatabaseStrategyReaderPluginInterface>
     */
    public function getDatabaseStrategyReaderPlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_DATABASE_STRATEGY_READER_PLUGINS);
    }
}
