<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MultiCart\CartOperation\CartCreator;
use Spryker\Client\MultiCart\CartOperation\CartCreatorInterface;
use Spryker\Client\MultiCart\CartOperation\CartDeleteChecker;
use Spryker\Client\MultiCart\CartOperation\CartDeleteCheckerInterface;
use Spryker\Client\MultiCart\CartOperation\CartReader;
use Spryker\Client\MultiCart\CartOperation\CartReaderInterface;
use Spryker\Client\MultiCart\CartOperation\CartUpdater;
use Spryker\Client\MultiCart\CartOperation\CartUpdaterInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToCustomerClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToMessengerClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToZedRequestClientInterface;
use Spryker\Client\MultiCart\Dependency\Service\MultiCartToUtilDateTimeServiceInterface;
use Spryker\Client\MultiCart\QuoteStorageSynchronizer\CustomerLoginQuoteSync;
use Spryker\Client\MultiCart\QuoteStorageSynchronizer\CustomerLoginQuoteSyncInterface;
use Spryker\Client\MultiCart\Storage\MultiCartStorage;
use Spryker\Client\MultiCart\Storage\MultiCartStorageInterface;
use Spryker\Client\MultiCart\Zed\MultiCartZedStub;
use Spryker\Client\MultiCart\Zed\MultiCartZedStubInterface;

/**
 * @method \Spryker\Client\MultiCart\MultiCartConfig getConfig()
 */
class MultiCartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MultiCart\Zed\MultiCartZedStubInterface
     */
    public function createMultiCartZedStub(): MultiCartZedStubInterface
    {
        return new MultiCartZedStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\MultiCart\MultiCartConfig
     */
    public function getMultiCartConfig(): MultiCartConfig
    {
        return $this->getConfig();
    }

    /**
     * @return \Spryker\Client\MultiCart\Storage\MultiCartStorageInterface
     */
    public function createMultiCartStorage(): MultiCartStorageInterface
    {
        return new MultiCartStorage($this->getSessionClient());
    }

    /**
     * @return \Spryker\Client\MultiCart\QuoteStorageSynchronizer\CustomerLoginQuoteSyncInterface
     */
    public function createCustomerLoginQuoteSave(): CustomerLoginQuoteSyncInterface
    {
        return new CustomerLoginQuoteSync(
            $this->getPersistentCartClient(),
            $this->getQuoteClient()
        );
    }

    /**
     * @return \Spryker\Client\MultiCart\CartOperation\CartUpdaterInterface
     */
    public function createCartUpdater(): CartUpdaterInterface
    {
        return new CartUpdater(
            $this->createMultiCartZedStub(),
            $this->getPersistentCartClient(),
            $this->getQuoteClient(),
            $this->getCustomerClient(),
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\MultiCart\CartOperation\CartCreatorInterface
     */
    public function createCartCreator(): CartCreatorInterface
    {
        return new CartCreator(
            $this->getPersistentCartClient(),
            $this->getQuoteClient(),
            $this->getCustomerClient(),
            $this->getDateTimeService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\MultiCart\CartOperation\CartReaderInterface
     */
    public function createCartReader(): CartReaderInterface
    {
        return new CartReader(
            $this->createMultiCartZedStub()
        );
    }

    /**
     * @return \Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface
     */
    public function getQuoteClient(): MultiCartToQuoteClientInterface
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\MultiCart\Dependency\Client\MultiCartToCustomerClientInterface
     */
    public function getCustomerClient(): MultiCartToCustomerClientInterface
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\MultiCart\Dependency\Client\MultiCartToMessengerClientInterface
     */
    public function getMessengerClient(): MultiCartToMessengerClientInterface
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_MESSENGER);
    }

    /**
     * @return \Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface
     */
    public function getSessionClient(): MultiCartToSessionClientInterface
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\MultiCart\Dependency\Client\MultiCartToZedRequestClientInterface
     */
    public function getZedRequestClient(): MultiCartToZedRequestClientInterface
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): MultiCartToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Client\MultiCart\Dependency\Service\MultiCartToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): MultiCartToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::SERVICE_DATETIME);
    }

    /**
     * @return \Spryker\Client\MultiCart\CartOperation\CartDeleteCheckerInterface
     */
    public function createCartDeleteChecker(): CartDeleteCheckerInterface
    {
        return new CartDeleteChecker(
            $this->createMultiCartStorage()
        );
    }
}
