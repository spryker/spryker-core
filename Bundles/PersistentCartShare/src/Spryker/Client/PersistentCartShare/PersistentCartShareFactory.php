<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PersistentCartShare\CartShareOption\CartShareOptionReader;
use Spryker\Client\PersistentCartShare\CartShareOption\CartShareOptionReaderInterface;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientInterface;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToResourceShareClientInterface;
use Spryker\Client\PersistentCartShare\ResourceShare\ResourceShareRequestBuilder;

class PersistentCartShareFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PersistentCartShare\CartShareOption\CartShareOptionReaderInterface
     */
    public function createCartShareOptionReader(): CartShareOptionReaderInterface
    {
        return new CartShareOptionReader(
            $this->getCartShareOptionPlugins()
        );
    }

    /**
     * @return \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface[]
     */
    public function getCartShareOptionPlugins(): array
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::PLUGINS_CART_SHARE_OPTION);
    }

    /**
     * @return \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToResourceShareClientInterface
     */
    public function getResourceShareClient(): PersistentCartShareToResourceShareClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::CLIENT_RESOURCE_SHARE);
    }

    /**
     * @return \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientInterface
     */
    public function getCustomerClient(): PersistentCartShareToCustomerClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\PersistentCartShare\ResourceShare\ResourceShareRequestBuilder
     */
    public function createResourceShareRequestBuilder(): ResourceShareRequestBuilder
    {
        return new ResourceShareRequestBuilder($this->getCustomerClient());
    }
}
