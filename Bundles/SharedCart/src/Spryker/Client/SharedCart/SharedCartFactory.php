<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SharedCart\CartSharer\CartSharer;
use Spryker\Client\SharedCart\CartSharer\CartSharerInterface;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToCartClientInterface;
use Spryker\Client\SharedCart\Zed\SharedCartStub;
use Spryker\Client\SharedCart\Zed\SharedCartStubInterface;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class SharedCartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SharedCart\CartSharer\CartSharerInterface
     */
    public function createCartSharer(): CartSharerInterface
    {
        return new CartSharer(
            $this->createZedSharedCartStub(),
            $this->getMultiCartClient(),
            $this->getPersistentCartClient(),
            $this->getMessengerClient()
        );
    }

    /**
     * @return \Spryker\Client\SharedCart\Zed\SharedCartStubInterface
     */
    public function createZedSharedCartStub(): SharedCartStubInterface
    {
        return new SharedCartStub($this->getZedRequestClient());
    }
    
    /**
     * @return \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCartClientInterface
     */
    public function getCartClient(): SharedCartToCartClientInterface
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMessengerClientInterface
     */
    public function getMessengerClient()
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::CLIENT_MESSENGER);
    }

    /**
     * @return \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface
     */
    public function getMultiCartClient()
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::CLIENT_MULTI_CART);
    }

    /**
     * @return \Spryker\Client\SharedCart\Dependency\Client\SharedCartToPersistentCartClientInterface
     */
    public function getPersistentCartClient()
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
