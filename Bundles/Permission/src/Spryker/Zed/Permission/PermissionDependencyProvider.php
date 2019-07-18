<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission;

use Exception;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Permission\PermissionConfig getConfig()
 */
class PermissionDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PERMISSION = 'CLIENT_PERMISSION';
    public const PLUGINS_PERMISSION = 'PLUGINS_PERMISSION';
    public const PLUGINS_PERMISSION_STORAGE = 'PLUGINS_PERMISSION_STORAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addPermissionStoragePlugins($container);
        $container = $this->addPermissionPlugins($container);
        $container = $this->addPermissionClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionPlugins(Container $container)
    {
        $container[static::PLUGINS_PERMISSION] = function (Container $container) {
            return $this->getPermissionPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface[]
     */
    protected function getPermissionPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionStoragePlugins(Container $container)
    {
        $container[static::PLUGINS_PERMISSION_STORAGE] = function (Container $container) {
            return $this->getPermissionStoragePlugins();
        };

        return $container;
    }

    /**
     * @throws \Exception
     *
     * @return \Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface[]
     */
    protected function getPermissionStoragePlugins(): array
    {
        throw new Exception('Please set an array of permission storage plugins, 
        all implementing the interface \Spryker\Zed\Permission\Communication\Plugin\PermissionStoragePluginInterface');
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionClient(Container $container)
    {
        $container[static::CLIENT_PERMISSION] = function (Container $container) {
            return $container->getLocator()->permission()->client();
        };

        return $container;
    }
}
