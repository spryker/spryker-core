<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerInterface;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToLocaleInterface;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageInterface;
use Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceInterface;
use Spryker\Client\ShoppingListStorage\KeyBuilder\ShoppingListStorageKeyBuilder;
use Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorage;
use Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorageInterface;

class ShoppingListStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerInterface
     */
    public function getCustomerClient(): ShoppingListStorageToCustomerInterface
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::SHOPPING_LIST_STORAGE_CUSTOMER_CLIENT);
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageInterface
     */
    public function getStorageClient(): ShoppingListStorageToStorageInterface
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::SHOPPING_LIST_STORAGE_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\KeyBuilder\ShoppingListStorageKeyBuilder
     */
    public function createKeyBuilder(): ShoppingListStorageKeyBuilder
    {
        return new ShoppingListStorageKeyBuilder();
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToLocaleInterface
     */
    public function getLocaleClient(): ShoppingListStorageToLocaleInterface
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::SHOPPING_LIST_STORAGE_LOCALE_CLIENT);
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorageInterface
     */
    public function createShoppingListCustomerStorage(): ShoppingListCustomerStorageInterface
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
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::SHOPPING_LIST_STORAGE_SYNCHRONIZATION_SERVICE);
    }
}
