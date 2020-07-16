<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientBridge;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientBridge;
use Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceBridge;

class ProductBundleStorageDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addSynchronizationService($container);
        $container = $this->addStorageClient($container);
        $container = $this->addProductStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new ProductBundleStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new ProductBundleStorageToStorageClientBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new ProductBundleStorageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        });

        return $container;
    }
}
