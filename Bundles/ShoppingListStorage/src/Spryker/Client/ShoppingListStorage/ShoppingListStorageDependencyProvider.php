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
    public const CLIENT_CUSTOMER = 'CLIENT:SHOPPING_LIST_STORAGE:CLIENT_CUSTOMER';
    public const KV_STORAGE = 'CLIENT:SHOPPING_LIST_STORAGE:KV_STORAGE';
    public const CLIENT_LOCALE = 'CLIENT:SHOPPING_LIST_STORAGE:CLIENT_LOCALE';
    public const SERVICE_SYNCHRONIZATION = 'CLIENT:SHOPPING_LIST_STORAGE:SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addKvStorage($container);
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
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new ShoppingListStorageToCustomerBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addKvStorage(Container $container)
    {
        $container[static::KV_STORAGE] = function (Container $container) {
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
        $container[static::CLIENT_LOCALE] = function (Container $container) {
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
        $container[static::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new ShoppingListStorageToSynchronizationServiceBridge(
                $container->getLocator()->synchronization()->service()
            );
        };

        return $container;
    }
}
