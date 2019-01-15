<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

use Spryker\Client\ContentStorage\Dependency\Client\ContentStorageToStorageClientBridge;
use Spryker\Client\ContentStorage\Dependency\Service\ContentStorageToSynchronizationServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class ContentStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    public const PLUGIN_CONTENT_ITEM_PLUGINS = 'PLUGIN_CONTENT_ITEM_PLUGINS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addClientStorage($container);
        $container = $this->addServiceSynchronization($container);
        $container = $this->addContentItemPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addContentItemPlugins(Container $container)
    {
        $container[static::PLUGIN_CONTENT_ITEM_PLUGINS] = function () {
            return $this->getContentItemPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface[]
     */
    protected function getContentItemPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addClientStorage(Container $container)
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new ContentStorageToStorageClientBridge(
                $container->getLocator()->storage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addServiceSynchronization(Container $container)
    {
        $container[static::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new ContentStorageToSynchronizationServiceBridge(
                $container->getLocator()->synchronization()->service()
            );
        };

        return $container;
    }
}
