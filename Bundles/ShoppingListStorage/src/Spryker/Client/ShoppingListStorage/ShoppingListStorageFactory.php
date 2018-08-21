<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerClientInterface;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageClientInterface;
use Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceInterface;
use Spryker\Client\ShoppingListStorage\OutdateChecker\ShoppingListCollectionOutdateChecker;
use Spryker\Client\ShoppingListStorage\OutdateChecker\ShoppingListCollectionOutdateCheckerInterface;
use Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorage;
use Spryker\Client\ShoppingListStorage\Storage\ShoppingListCustomerStorageInterface;

class ShoppingListStorageFactory extends AbstractFactory
{
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
     * @return \Spryker\Client\ShoppingListStorage\OutdateChecker\ShoppingListCollectionOutdateCheckerInterface
     */
    public function createShoppingListCollectionOutdateChecker(): ShoppingListCollectionOutdateCheckerInterface
    {
        return new ShoppingListCollectionOutdateChecker(
            $this->getCustomerClient(),
            $this->createShoppingListCustomerStorage()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageClientInterface
     */
    public function getStorageClient(): ShoppingListStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::SHOPPING_LIST_STORAGE_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ShoppingListStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::SHOPPING_LIST_STORAGE_SYNCHRONIZATION_SERVICE);
    }

    /**
     * @return \Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerClientInterface
     */
    public function getCustomerClient(): ShoppingListStorageToCustomerClientInterface
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::SHOPPING_LIST_STORAGE_CUSTOMER_CLIENT);
    }
}
