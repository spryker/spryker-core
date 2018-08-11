<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerInterface;
use Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceInterface;
use Spryker\Client\ShoppingListStorage\KeyBuilder\ShoppingListStorageKeyBuilder;
use Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorage;

class ShoppingListStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerInterface
     */
    public function getCustomerClient(): ShoppingListStorageToCustomerInterface
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\KeyBuilder\ShoppingListStorageKeyBuilder
     */
    public function createKeyBuilder()
    {
        return new ShoppingListStorageKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Product\Dependency\Client\ProductToLocaleInterface
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorage
     */
    public function createShoppingListStorage()
    {
        return new ShoppingListCustomerStorage(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ShoppingListStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
