<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\NavigationStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\NavigationStorage\Dependency\Client\NavigationStorageToStorageClientBridge;
use Spryker\Client\NavigationStorage\Dependency\Service\NavigationStorageToSynchronizationServiceBridge;

class NavigationStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new NavigationStorageToStorageClientBridge($container->getLocator()->storage()->client());
        };

        $container[static::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new NavigationStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }
}
