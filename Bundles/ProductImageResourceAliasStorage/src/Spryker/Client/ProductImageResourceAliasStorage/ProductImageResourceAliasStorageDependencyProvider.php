<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageResourceAliasStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductImageResourceAliasStorage\Dependency\Client\ProductImageResourceAliasStorageToStorageClientBridge;
use Spryker\Client\ProductImageResourceAliasStorage\Dependency\Service\ProductImageResourceAliasStorageToSynchronizationServiceBridge;

class ProductImageResourceAliasStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container)
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new ProductImageResourceAliasStorageToStorageClientBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container)
    {
        $container[static::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new ProductImageResourceAliasStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }
}
