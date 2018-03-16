<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MultiCart\QuoteStorageSynchronizer\CustomerLoginQuoteSync;
use Spryker\Client\MultiCart\Session\MultiCartSession;
use Spryker\Client\MultiCart\Zed\MultiCartStub;

/**
 * @method \Spryker\Client\MultiCart\MultiCartConfig getConfig()
 */
class MultiCartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\MultiCart\Zed\MultiCartStubInterface
     */
    public function createZedMultiCartStub()
    {
        return new MultiCartStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\MultiCart\MultiCartConfig
     */
    public function getBundleConfig()
    {
        return $this->getConfig();
    }

    /**
     * @return \Spryker\Client\MultiCart\Session\MultiCartSessionInterface
     */
    public function createMultiCartSession()
    {
        return new MultiCartSession($this->getSessionClient());
    }

    /**
     * @return \Spryker\Client\MultiCart\QuoteStorageSynchronizer\CustomerLoginQuoteSyncInterface
     */
    public function createCustomerLoginQuoteSave()
    {
        return new CustomerLoginQuoteSync(
            $this->getPersistentCartClent(),
            $this->getQuoteClient()
        );
    }

    /**
     * @return \Spryker\Client\MultiCart\Dependency\Client\MultiCartToMessengerClientInterface
     */
    public function getMessengerClient()
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_MESSENGER);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface
     */
    protected function getPersistentCartClent()
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::CLIENT_PERSISTENT_CART);
    }
}
