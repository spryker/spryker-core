<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductQuantityStorage\Dependency\Client\ProductQuantityStorageToStorageClientBridge;
use Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToProductQuantityServiceBridge;
use Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToProductQuantityServiceInterface;
use Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToSynchronizationServiceBridge;

class ProductQuantityStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    public const SERVICE_PRODUCT_QUANTITY = 'SERVICE_PRODUCT_QUANTITY';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addProductQuantityService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new ProductQuantityStorageToStorageClientBridge($container->getLocator()->storage()->client());
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
            return new ProductQuantityStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductQuantityService(Container $container): Container
    {
        $container[static::SERVICE_PRODUCT_QUANTITY] = function (Container $container): ProductQuantityStorageToProductQuantityServiceInterface {
            return new ProductQuantityStorageToProductQuantityServiceBridge($container->getLocator()->productQuantity()->service());
        };

        return $container;
    }
}
