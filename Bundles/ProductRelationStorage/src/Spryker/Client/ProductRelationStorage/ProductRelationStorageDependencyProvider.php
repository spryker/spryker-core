<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientAdapter;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToStorageClientBridge;
use Spryker\Client\ProductRelationStorage\Dependency\Service\ProductRelationStorageToSynchronizationServiceBridge;

/**
 * @method \Spryker\Client\ProductRelationStorage\ProductRelationStorageConfig getConfig()
 */
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
    public function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new ProductRelationStorageToStorageClientBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new ProductRelationStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function addProductStorageClient(Container $container): Container
    {
        $container->set(self::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new ProductRelationStorageToProductStorageClientAdapter($container->getLocator()->productStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addRelatedProductExpanderPlugins(Container $container): Container
    {
        $container->set(self::PLUGIN_RELATED_PRODUCT_EXPANDERS, function () {
            return $this->getRelatedProductExpanderPlugins();
        });

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
