<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToSessionClientBridge;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListBridge;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToStorageBridge;

class ShoppingListSessionDependencyProvider extends AbstractDependencyProvider
{
    const SHOPPING_LIST_STORAGE = 'SHOPPING_LIST_STORAGE';
    const CLIENT_SESSION = 'CLIENT_SESSION';
    const SHOPPING_LIST_CLIENT = 'SHOPPING_LIST_CLIENT';
    const PLUGINS_SHOPPING_LIST_COLLECTION_OUTDATED = 'PLUGINS_SHOPPING_LIST_COLLECTION_OUTDATED';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addShoppingListClient($container);
        $container = $this->addShoppingListCollectionOutdatedPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container)
    {
        $container[static::SHOPPING_LIST_STORAGE] = function (Container $container) {
            return new ShoppingListSessionToStorageBridge($container->getLocator()->storage()->client());
        };
        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return new ShoppingListSessionToSessionClientBridge($container->getLocator()->session()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addShoppingListClient(Container $container): Container
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return new ShoppingListSessionToShoppingListBridge($container->getLocator()->shoppingList()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addShoppingListCollectionOutdatedPlugins(Container $container): Container
    {
        $container[static::PLUGINS_SHOPPING_LIST_COLLECTION_OUTDATED] = function () {
            return $this->getShoppingListCollectionOutdatedPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Plugin\ShoppingListCollectionOutdatedPluginInterface[]
     */
    protected function getShoppingListCollectionOutdatedPlugins(): array
    {
        return [];
    }
}
