<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToCustomerClientBridge;
use Spryker\Client\ShoppingListStorage\Dependency\Client\ShoppingListStorageToStorageClientBridge;
use Spryker\Client\ShoppingListStorage\Dependency\Service\ShoppingListStorageToSynchronizationServiceBridge;

class ShoppingListStorageDependencyProvider extends AbstractDependencyProvider
{
    public const SHOPPING_LIST_STORAGE_CUSTOMER_CLIENT = 'SHOPPING_LIST_STORAGE_CUSTOMER_CLIENT';
    public const SHOPPING_LIST_STORAGE_STORAGE_CLIENT = 'SHOPPING_LIST_STORAGE_STORAGE_CLIENT';
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
            return new ShoppingListStorageToCustomerClientBridge($container->getLocator()->customer()->client());
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
            return new ShoppingListStorageToStorageClientBridge($container->getLocator()->storage()->client());
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
