<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Storage\Dependency\Client\StorageToLocaleClientBridge;
use Spryker\Client\Storage\Dependency\Client\StorageToStoreClientBridge;
use Spryker\Shared\Kernel\Store;

class StorageDependencyProvider extends AbstractDependencyProvider
{
    public const STORE_CLIENT = 'STORE_CLIENT';
    public const LOCALE_CLIENT = 'LOCALE_CLIENT';
    public const STORAGE_CLIENT = 'STORAGE_CLIENT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container[static::STORAGE_CLIENT] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        $container[static::STORE_CLIENT] = function (Container $container) {
            return new StorageToStoreClientBridge($container->getLocator()->store()->client());
        };

        $container[static::LOCALE_CLIENT] = function (Container $container) {
            return new StorageToLocaleClientBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }
}
