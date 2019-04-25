<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StorageRouter;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientBridge;

/**
 * @method \Spryker\Yves\StorageRouter\StorageRouterConfig getConfig()
 */
class StorageRouterDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_URL_STORAGE = 'CLIENT_URL_STORAGE';
    public const PLUGIN_RESOURCE_CREATORS = 'PLUGIN_RESOURCE_CREATORS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addUrlStorageClient($container);
        $container = $this->addResourceCreatorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUrlStorageClient(Container $container): Container
    {
        $container[static::CLIENT_URL_STORAGE] = function (Container $container) {
            return new StorageRouterToUrlStorageClientBridge($container->getLocator()->urlStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addResourceCreatorPlugins(Container $container): Container
    {
        $container[static::PLUGIN_RESOURCE_CREATORS] = function () {
            return $this->getResourceCreatorPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface[]
     */
    protected function getResourceCreatorPlugins()
    {
        return [];
    }
}
