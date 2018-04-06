<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSync;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\ChangeRequestExtendPluginExecutor;
use Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutor;
use Spryker\Client\PersistentCart\Zed\PersistentCartStub;

class PersistentCartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface
     */
    public function createZedPersistentCartStub()
    {
        return new PersistentCartStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\QuoteUpdatePluginExecutorInterface
     */
    public function createQuoteUpdatePluginExecutor()
    {
        return new QuoteUpdatePluginExecutor($this->getQuoteUpdatePlugins());
    }

    /**
     * @return \Spryker\Client\PersistentCart\QuoteUpdatePluginExecutor\ChangeRequestExtendPluginExecutorInterface
     */
    public function createChangeRequestExtendPluginExecutor()
    {
        return new ChangeRequestExtendPluginExecutor($this->getChangeRequestExtendPlugins());
    }

    /**
     * @return \Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSyncInterface
     */
    public function createCustomerLoginQuoteSync()
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
    protected function getQuoteUpdatePlugins()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_UPDATE);
    }

    /**
     * @return \Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface[]
     */
    protected function getChangeRequestExtendPlugins()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_CHANGE_REQUEST_EXTEND);
    }
}
