<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Spryker\Client\CategoryImageStorage\Dependency\CategoryImageStorageToSynchronizationServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToSessionClientBridge;

/**
 * @method \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig getConfig()
 */
class ProductConfigurationStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addSessionClient($container);
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, function (Container $container) {
            return new ProductConfigurationStorageToSessionClientBridge($container->getLocator()->session()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new ProductConfigurationStorageToSessionClientBridge($container->getLocator()->session()->client());
        });

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
            return new CategoryImageStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }
}
