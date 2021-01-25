<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductResourceAliasStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToProductStorageClientBridge;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Client\ProductResourceAliasStorageToStorageClientBridge;
use Spryker\Client\ProductResourceAliasStorage\Dependency\Service\ProductResourceAliasStorageToSynchronizationServiceBridge;
use Spryker\Shared\Kernel\Store;

class ProductResourceAliasStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    public const STORE = 'STORE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addStorageClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container)
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new ProductResourceAliasStorageToStorageClientBridge($container->getLocator()->storage()->client());
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
            return new ProductResourceAliasStorageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container)
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new ProductResourceAliasStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container->set(static::STORE, function () {
            return Store::getInstance();
        });

        return $container;
    }
}
