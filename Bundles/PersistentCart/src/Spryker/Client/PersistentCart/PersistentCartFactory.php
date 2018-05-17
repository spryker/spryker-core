<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSync;
use Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSyncInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\ChangeRequestExtendPluginExecutor;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\ChangeRequestExtendPluginExecutorInterface;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutor;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteCreator;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteCreatorInterface;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteDeleter;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteDeleterInterface;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteUpdater;
use Spryker\Client\PersistentCart\QuoteWriter\QuoteUpdaterInterface;
use Spryker\Client\PersistentCart\Zed\PersistentCartStub;
use Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class PersistentCartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PersistentCart\QuoteWriter\QuoteCreatorInterface
     */
    public function createQuoteCreator(): QuoteCreatorInterface
    {
        return new QuoteCreator(
            $this->getQuoteClient(),
            $this->getZedRequestClient(),
            $this->createZedPersistentCartStub(),
            $this->createQuoteUpdatePluginExecutor()
        );
    }

    /**
     * @return \Spryker\Client\PersistentCart\QuoteWriter\QuoteUpdaterInterface
     */
    public function createQuoteUpdater(): QuoteUpdaterInterface
    {
        return new QuoteUpdater(
            $this->getQuoteClient(),
            $this->createZedPersistentCartStub(),
            $this->createQuoteUpdatePluginExecutor()
        );
    }

    /**
     * @return \Spryker\Client\PersistentCart\QuoteWriter\QuoteDeleterInterface
     */
    public function createQuoteDeleter(): QuoteDeleterInterface
    {
        return new QuoteDeleter(
            $this->getQuoteClient(),
            $this->getZedRequestClient(),
            $this->getCustomerClient(),
            $this->createZedPersistentCartStub(),
            $this->createQuoteUpdatePluginExecutor()
        );
    }

    /**
     * @return \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface
     */
    public function getQuoteClient(): PersistentCartToQuoteClientInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface
     */
    public function createZedPersistentCartStub(): PersistentCartStubInterface
    {
        return new PersistentCartStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface
     */
    public function getCustomerClient(): PersistentCartToCustomerClientInterface
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface
     */
    public function createQuoteUpdatePluginExecutor(): QuoteUpdatePluginExecutorInterface
    {
        return new QuoteUpdatePluginExecutor($this->getQuoteUpdatePlugins());
    }

    /**
     * @return \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\ChangeRequestExtendPluginExecutorInterface
     */
    public function createChangeRequestExtendPluginExecutor(): ChangeRequestExtendPluginExecutorInterface
    {
        return new ChangeRequestExtendPluginExecutor($this->getChangeRequestExtendPlugins());
    }

    /**
     * @return \Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSyncInterface
     */
    public function createCustomerLoginQuoteSync(): CustomerLoginQuoteSyncInterface
    {
        return new CustomerLoginQuoteSync(
            $this->createZedPersistentCartStub(),
            $this->getQuoteClient(),
            $this->createQuoteUpdatePluginExecutor()
        );
    }

    /**
     * @return \Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface[]
     */
    protected function getQuoteUpdatePlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_UPDATE);
    }

    /**
     * @return \Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface[]
     */
    protected function getChangeRequestExtendPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_CHANGE_REQUEST_EXTEND);
    }
}
