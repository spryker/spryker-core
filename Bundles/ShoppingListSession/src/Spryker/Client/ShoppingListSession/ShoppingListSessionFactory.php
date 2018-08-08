<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Client\ShoppingList\ShoppingListClientInterface;
use Spryker\Client\ShoppingListSession\ShoppingList\ShoppingListReader;
use Spryker\Client\ShoppingListSession\Storage\ShoppingListStorage;
use Spryker\Client\ShoppingListSession\Storage\ShoppingListStorageInterface;

class ShoppingListSessionFactory extends AbstractFactory
{
    const PLUGINS_SHOPPING_LIST_COLLECTION_OUTDATED = 'PLUGINS_SHOPPING_LIST_COLLECTION_OUTDATED';

    /**
     * @return \Spryker\Client\ShoppingListSession\Storage\ShoppingListStorageInterface
     */
    public function createShoppingListStorage()
    {
        return new ShoppingListStorage(
            $this->getStorage(),
            $this->getSessionClient()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\ShoppingList\ShoppingListReader
     */
    public function createShoppingListReader()
    {
        return new ShoppingListReader(
            $this->createShoppingListStorage(),
            $this->getShoppingListClient(),
            $this->getShoppingListCollectionOutdatedPlugins()
        );
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Storage\ShoppingListStorageInterface
     */
    protected function getStorage(): ShoppingListStorageInterface
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_STORAGE);
    }

    /**
     * @return \Spryker\Client\ShoppingList\ShoppingListClientInterface
     */
    protected function getShoppingListClient(): ShoppingListClientInterface
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_CLIENT);
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[]
     */
    protected function getShoppingListCollectionOutdatedPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListSessionDependencyProvider::PLUGINS_SHOPPING_LIST_COLLECTION_OUTDATED);
    }
}
