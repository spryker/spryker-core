<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToLocaleBridge;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientBridge;
use Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceBridge;

class ProductStorageDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_LOCALE = 'CLIENT_LOCALE';
    const CLIENT_STORAGE = 'CLIENT_STORAGE';
    const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new ProductStorageToStorageClientBridge($container->getLocator()->storage()->client());
        };

        $container[static::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new ProductStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new ProductStorageToLocaleBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }
}
