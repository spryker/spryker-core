<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientBridge;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToStorageClientBridge;
use Spryker\Client\ProductRelationStorage\Dependency\Service\ProductRelationStorageToSynchronizationServiceBridge;

class ProductRelationStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const PLUGIN_RELATED_PRODUCT_EXPANDERS = 'PLUGIN_RELATED_PRODUCT_EXPANDERS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addRelatedProductExpanderPlugins($container);

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
            return new ProductRelationStorageToStorageClientBridge($container->getLocator()->storage()->client());
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
            return new ProductRelationStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function addProductStorageClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new ProductRelationStorageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addRelatedProductExpanderPlugins(Container $container)
    {
        $container[self::PLUGIN_RELATED_PRODUCT_EXPANDERS] = function () {
            return $this->getRelatedProductExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getRelatedProductExpanderPlugins()
    {
        return [];
    }
}
