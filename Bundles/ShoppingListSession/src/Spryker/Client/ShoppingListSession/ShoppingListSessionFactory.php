<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToCustomerClientBridgeInterface;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToSessionClientBridgeInterface;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListBridgeInterface;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToStorageBridgeInterface;
use Spryker\Client\ShoppingListSession\ShoppingList\ShoppingListSessionReader;
use Spryker\Client\ShoppingListSession\ShoppingList\ShoppingListSessionReaderInterface;
use Spryker\Client\ShoppingListSession\ShoppingListSessionPluginsExecutor\ShoppingListSessionPluginsExecutor;
use Spryker\Client\ShoppingListSession\ShoppingListSessionPluginsExecutor\ShoppingListSessionPluginsExecutorInterface;
use Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionSessionStorage;
use Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionStorageInterface;

class ShoppingListSessionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionStorageInterface
     */
    public function createShoppingListSessionStorage(): ShoppingListSessionStorageInterface
    {
        return new ShoppingListSessionSessionStorage(
            $this->getStorageClient(),
            $this->getSessionClient()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\ShoppingList\ShoppingListSessionReaderInterface
     */
    public function createShoppingListSessionReader(): ShoppingListSessionReaderInterface
    {
        return new ShoppingListSessionReader(
            $this->createShoppingListSessionStorage(),
            $this->getShoppingListClient(),
            $this->createShoppingListCollectionOutdatedPluginsExecutor(),
            $this->getCustomerClient()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToSessionClientBridgeInterface
     */
    public function getSessionClient(): ShoppingListSessionToSessionClientBridgeInterface
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_SESSION_CLIENT);
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToStorageBridgeInterface
     */
    protected function getStorageClient(): ShoppingListSessionToStorageBridgeInterface
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListBridgeInterface
     */
    protected function getShoppingListClient(): ShoppingListSessionToShoppingListBridgeInterface
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_SHOPPING_LIST_CLIENT);
    }

    /**
     * @return \Spryker\Client\ShoppingListSessionExtension\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[]
     */
    protected function getShoppingListCollectionOutdatedPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_COLLECTION_OUTDATED_PLUGINS);
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\ShoppingListSessionPluginsExecutor\ShoppingListSessionPluginsExecutorInterface
     */
    protected function createShoppingListCollectionOutdatedPluginsExecutor(): ShoppingListSessionPluginsExecutorInterface
    {
        return new ShoppingListSessionPluginsExecutor(
            $this->getShoppingListCollectionOutdatedPlugins()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToCustomerClientBridgeInterface
     */
    protected function getCustomerClient(): ShoppingListSessionToCustomerClientBridgeInterface
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_CUSTOMER_CLIENT);
    }
}
