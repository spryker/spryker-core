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

/**
 * @method \Spryker\Client\Storage\StorageConfig getConfig()
 */
class StorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const STORAGE_CLIENT = 'storage client';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container[self::STORAGE_CLIENT] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        $container[static::CLIENT_STORE] = function (Container $container) {
            return new StorageToStoreClientBridge($container->getLocator()->store()->client());
        };

        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new StorageToLocaleClientBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }
}
