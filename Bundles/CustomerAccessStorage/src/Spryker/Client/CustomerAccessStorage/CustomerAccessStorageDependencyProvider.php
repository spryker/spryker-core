<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessStorage;

use Spryker\Client\CustomerAccessStorage\Dependency\Client\CustomerAccessStorageToStorageClientBridge;
use Spryker\Client\CustomerAccessStorage\Dependency\Service\CustomerAccessStorageToSynchronizationServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CustomerAccessStorageDependencyProvider extends AbstractDependencyProvider
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
        $this->addStorageClient($container);
        $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function addStorageClient(Container $container)
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new CustomerAccessStorageToStorageClientBridge($container->getLocator()->storage()->client());
        };
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function addSynchronizationService(Container $container)
    {
        $container[self::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new CustomerAccessStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };
    }
}
