<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerBridge;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToLocaleBridge;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageBridge;
use Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceBridge;

class ShoppingListStorageDependencyProvider extends AbstractDependencyProvider
{
    public const SHOPPING_LIST_STORAGE_CUSTOMER_CLIENT = 'SHOPPING_LIST_STORAGE_CUSTOMER_CLIENT';
    public const SHOPPING_LIST_STORAGE_STORAGE_CLIENT = 'SHOPPING_LIST_STORAGE_STORAGE_CLIENT';
    public const SHOPPING_LIST_STORAGE_LOCALE_CLIENT = 'SHOPPING_LIST_STORAGE_LOCALE_CLIENT';
    public const SHOPPING_LIST_STORAGE_SYNCHRONIZATION_SERVICE = 'SHOPPING_LIST_STORAGE_SYNCHRONIZATION_SERVICE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addStorageClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::SHOPPING_LIST_STORAGE_CUSTOMER_CLIENT] = function (Container $container) {
            return new ShoppingListStorageToCustomerBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container[static::SHOPPING_LIST_STORAGE_STORAGE_CLIENT] = function (Container $container) {
            return new ShoppingListStorageToStorageBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container[static::SHOPPING_LIST_STORAGE_LOCALE_CLIENT] = function (Container $container) {
            return new ShoppingListStorageToLocaleBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container[static::SHOPPING_LIST_STORAGE_SYNCHRONIZATION_SERVICE] = function (Container $container) {
            return new ShoppingListStorageToSynchronizationServiceBridge(
                $container->getLocator()->synchronization()->service()
            );
        };

        return $container;
    }
}
