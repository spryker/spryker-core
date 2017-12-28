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
use Spryker\Shared\Kernel\Store;

class ProductStorageDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_LOCALE = 'CLIENT_LOCALE';
    const CLIENT_STORAGE = 'CLIENT_STORAGE';
    const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    const STORE = 'STORE';
    const PLUGIN_PRODUCT_VIEW_EXPANDERS = 'PLUGIN_STORAGE_PRODUCT_EXPANDERS';
    const SERVICE_ENCODING = 'SERVICE_ENCODING';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addEncodingService($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addStore($container);
        $container = $this->addProductViewExpanderPlugins($container);

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
            return new ProductStorageToStorageClientBridge($container->getLocator()->storage()->client());
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
            return new ProductStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container)
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new ProductStorageToLocaleBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductViewExpanderPlugins(Container $container)
    {
        $container[static::PLUGIN_PRODUCT_VIEW_EXPANDERS] = function () {
            return $this->getProductViewExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getProductViewExpanderPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addEncodingService(Container $container)
    {
        $container[static::SERVICE_ENCODING] = function (Container $container) {
            return new ProductStorageToSynchronizationServiceBridge($container->getLocator()->encoding()->service());
        };

        return $container;
    }
}
