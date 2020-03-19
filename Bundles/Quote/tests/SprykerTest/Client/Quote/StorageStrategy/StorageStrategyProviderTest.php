<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Quote\StorageStrategy;

use Codeception\Test\Unit;
use Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface;
use Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface;
use Spryker\Client\Quote\Exception\StorageStrategyNotFound;
use Spryker\Client\Quote\QuoteConfig;
use Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface;
use Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface;
use Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface;
use Spryker\Client\Quote\Session\QuoteSession;
use Spryker\Client\Quote\Session\QuoteSessionInterface;
use Spryker\Client\Quote\StorageStrategy\DatabaseStorageStrategy;
use Spryker\Client\Quote\StorageStrategy\SessionStorageStrategy;
use Spryker\Client\Quote\StorageStrategy\StorageStrategyProvider;
use Spryker\Client\Quote\StorageStrategy\StorageStrategyProviderInterface;
use Spryker\Client\Quote\Zed\QuoteStubInterface;
use Spryker\Client\Session\SessionClient;
use Spryker\Shared\Quote\QuoteConfig as SharedQuoteConfig;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Quote
 * @group StorageStrategy
 * @group StorageStrategyProviderTest
 * Add your own group annotations below this line
 */
class StorageStrategyProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testNotLoggedInCustomerCanUseSessionStorageOnly(): void
    {
        $customerClient = $this->createCustomerClientMock();
        $customerClient->method('isLoggedIn')->willReturn(false);

        $quoteConfig = $this->createQuoteConfigMock();
        $quoteConfig->method('getStorageStrategy')
            ->willReturn(SharedQuoteConfig::STORAGE_STRATEGY_DATABASE);

        $storageStrategyList = [
            $this->createSessionStorageStrategy(),
            $this->createDatabaseStorageStrategy($customerClient),
        ];

        $storageStrategyProvider = $this->createStorageStrategyProvider($quoteConfig, $storageStrategyList);
        $storageStrategy = $storageStrategyProvider->provideStorage();

        $this->assertInstanceOf(SessionStorageStrategy::class, $storageStrategy);
    }

    /**
     * @return void
     */
    public function testLoggedInCustomerCanUseSessionStorage(): void
    {
        $customerClient = $this->createCustomerClientMock();
        $customerClient->method('isLoggedIn')->willReturn(true);

        $quoteConfig = $this->createQuoteConfigMock();
        $quoteConfig->method('getStorageStrategy')
            ->willReturn(SharedQuoteConfig::STORAGE_STRATEGY_SESSION);

        $storageStrategyList = [
            $this->createSessionStorageStrategy(),
            $this->createDatabaseStorageStrategy($customerClient),
        ];

        $storageStrategyProvider = $this->createStorageStrategyProvider($quoteConfig, $storageStrategyList);
        $storageStrategy = $storageStrategyProvider->provideStorage();

        $this->assertInstanceOf(SessionStorageStrategy::class, $storageStrategy);
    }

    /**
     * @return void
     */
    public function testLoggedInCustomerCanUseDatabaseStorage(): void
    {
        $customerClient = $this->createCustomerClientMock();
        $customerClient->method('isLoggedIn')->willReturn(true);

        $quoteConfig = $this->createQuoteConfigMock();
        $quoteConfig->method('getStorageStrategy')
            ->willReturn(SharedQuoteConfig::STORAGE_STRATEGY_DATABASE);

        $storageStrategyList = [
            $this->createSessionStorageStrategy(),
            $this->createDatabaseStorageStrategy($customerClient),
        ];

        $storageStrategyProvider = $this->createStorageStrategyProvider($quoteConfig, $storageStrategyList);
        $storageStrategy = $storageStrategyProvider->provideStorage();

        $this->assertInstanceOf(DatabaseStorageStrategy::class, $storageStrategy);
    }

    /**
     * @return void
     */
    public function testUsingIncorrectStorageTypeLeadsToError(): void
    {
        $customerClient = $this->createCustomerClientMock();

        $quoteConfig = $this->createQuoteConfigMock();
        $quoteConfig->method('getStorageStrategy')
            ->willReturn('TestStorage');

        $storageStrategyList = [
            $this->createSessionStorageStrategy(),
            $this->createDatabaseStorageStrategy($customerClient),
        ];

        $this->expectException(StorageStrategyNotFound::class);

        $storageStrategyProvider = $this->createStorageStrategyProvider($quoteConfig, $storageStrategyList);
        $storageStrategyProvider->provideStorage();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Quote\QuoteConfig
     */
    protected function createQuoteConfigMock(): QuoteConfig
    {
        return $this->getMockBuilder(QuoteConfig::class)
            ->getMock();
    }

    /**
     * @param \Spryker\Client\Quote\QuoteConfig $quoteConfig
     * @param \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface[] $storageStrategyList
     *
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyProviderInterface
     */
    protected function createStorageStrategyProvider(QuoteConfig $quoteConfig, array $storageStrategyList): StorageStrategyProviderInterface
    {
        return new StorageStrategyProvider($quoteConfig, $storageStrategyList);
    }

    /**
     * @param \Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface $customerClient
     *
     * @return \Spryker\Client\Quote\StorageStrategy\DatabaseStorageStrategy
     */
    protected function createDatabaseStorageStrategy(QuoteToCustomerClientInterface $customerClient): DatabaseStorageStrategy
    {
        return new DatabaseStorageStrategy(
            $customerClient,
            $this->createQuoteZedStubMock(),
            $this->createQuoteSession(),
            $this->createQuoteLockStatusValidatorMock(),
            $this->createQuoteEditStatusValidatorMock(),
            $this->createQuoteLockerMock(),
            []
        );
    }

    /**
     * @return \Spryker\Client\Quote\StorageStrategy\SessionStorageStrategy
     */
    protected function createSessionStorageStrategy(): SessionStorageStrategy
    {
        return new SessionStorageStrategy(
            $this->createQuoteSession(),
            $this->createQuoteLockStatusValidatorMock(),
            $this->createQuoteEditStatusValidatorMock(),
            $this->createQuoteLockerMock()
        );
    }

    /**
     * @return \Spryker\Client\Quote\Session\QuoteSessionInterface
     */
    protected function createQuoteSession(): QuoteSessionInterface
    {
        $sessionContainer = new Session(new MockArraySessionStorage());
        $sessionClient = new SessionClient();
        $sessionClient->setContainer($sessionContainer);

        return new QuoteSession($sessionClient, $this->createCurrencyClientMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface
     */
    protected function createCustomerClientMock(): QuoteToCustomerClientInterface
    {
        return $this->getMockBuilder(QuoteToCustomerClientInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Quote\Zed\QuoteStubInterface
     */
    protected function createQuoteZedStubMock(): QuoteStubInterface
    {
        return $this->getMockBuilder(QuoteStubInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Quote\QuoteValidator\QuoteLockStatusValidatorInterface
     */
    protected function createQuoteLockStatusValidatorMock(): QuoteLockStatusValidatorInterface
    {
        return $this->createMock(QuoteLockStatusValidatorInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Quote\QuoteValidator\QuoteEditStatusValidatorInterface
     */
    protected function createQuoteEditStatusValidatorMock(): QuoteEditStatusValidatorInterface
    {
        return $this->createMock(QuoteEditStatusValidatorInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface
     */
    protected function createCurrencyClientMock(): QuoteToCurrencyClientInterface
    {
        return $this->getMockBuilder(QuoteToCurrencyClientInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Quote\QuoteLocker\QuoteLockerInterface
     */
    protected function createQuoteLockerMock(): QuoteLockerInterface
    {
        return $this->createMock(QuoteLockerInterface::class);
    }
}
