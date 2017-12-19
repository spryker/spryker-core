<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage;

use Spryker\Client\ProductCategoryStorage\Dependency\Client\ProductCategoryStorageToStorageBridge;
use Spryker\Client\ProductCategoryStorage\Dependency\Service\ProductCategoryStorageToSynchronizationServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class ProductCategoryStorageDependencyProvider extends AbstractDependencyProvider
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
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function addStorageClient(Container $container)
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new ProductCategoryStorageToStorageBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function addSynchronizationService(Container $container)
    {
        $container[self::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new ProductCategoryStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }
}
