<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingListSession\ShoppingList\ShoppingListSessionReader;
use Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionSessionStorage;

class ShoppingListSessionFactory extends AbstractFactory
{
    const PLUGINS_SHOPPING_LIST_COLLECTION_OUTDATED = 'PLUGINS_SHOPPING_LIST_COLLECTION_OUTDATED';

    /**
     * @return \Spryker\Client\ShoppingListSession\Storage\ShoppingListSessionStorageInterface
     */
    public function createShoppingListSessionStorage()
    {
        return new ShoppingListSessionSessionStorage(
            $this->getStorage(),
            $this->getSessionClient()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\ShoppingList\ShoppingListSessionReader
     */
    public function createShoppingListSessionReader()
    {
        return new ShoppingListSessionReader(
            $this->createShoppingListSessionStorage(),
            $this->getShoppingListClient(),
            $this->getShoppingListCollectionOutdatedPlugins()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToSessionClientBridgeInterface
     */
    public function getSessionClient()
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToStorageBridgeInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_STORAGE);
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListBridgeInterface
     */
    protected function getShoppingListClient()
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::CLIENT_SHOPPING_LIST);
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[]
     */
    protected function getShoppingListCollectionOutdatedPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::PLUGINS_SHOPPING_LIST_COLLECTION_OUTDATED);
    }
}
