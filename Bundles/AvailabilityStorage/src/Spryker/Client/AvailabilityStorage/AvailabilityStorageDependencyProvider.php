<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage;

use Spryker\Client\AvailabilityStorage\Dependency\Client\AvailabilityStorageToStorageBridge;
use Spryker\Client\AvailabilityStorage\Dependency\Service\AvailabilityStorageToSynchronizationServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class AvailabilityStorageDependencyProvider extends AbstractDependencyProvider
{

    const CLIENT_STORAGE = 'CLIENT_STORAGE';
    const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new AvailabilityStorageToStorageBridge($container->getLocator()->storage()->client());
        };

        $container[self::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new AvailabilityStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }

}
