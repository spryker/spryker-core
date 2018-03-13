<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSync;
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
    protected function getZedRequestClient()
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
     * @return \Spryker\Client\PersistentCart\QuoteStorageSynchronizer\CustomerLoginQuoteSyncInterface
     */
    public function createCustomerLoginQuoteSync()
    {
        return new CustomerLoginQuoteSync(
            $this->createZedPersistentCartStub(),
            $this->getQuoteClient()
        );
    }

    /**
     * @return \Spryker\Client\PersistentCart\Dependency\Plugin\QuoteUpdatePluginInterface[]
     */
    public function getQuoteUpdatePlugins()
    {
        return $this->getProvidedDependency(PersistentCartDependencyProvider::PLUGINS_QUOTE_UPDATE);
    }
}
