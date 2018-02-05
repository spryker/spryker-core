<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Permission;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Permission\Communication\Plugin\PermissionPluginInterface;
use Spryker\Zed\Permission\Communication\Plugin\PermissionStoragePluginInterface;

class PermissionDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_PERMISSION = 'PLUGINS_PERMISSION';
    public const PLUGIN_PERMISSION_STORAGE = 'PLUGIN_PERMISSION_STORAGE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addPermissionStoragePlugin($container);
        $container = $this->addPermissionPlugins($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addPermissionPlugins(Container $container)
    {
        $container[static::PLUGINS_PERMISSION] = function (Container $container) {
            return $this->getPermissionPlugins();
        };

        return $container;
    }

    /**
     * @return PermissionPluginInterface[]
     */
    protected function getPermissionPlugins()
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addPermissionStoragePlugin(Container $container)
    {
        $container[static::PLUGIN_PERMISSION_STORAGE] = function (Container $container) {
            return $this->getPermissionStoragePlugin();
        };

        return $container;
    }

    /**
     * @throws \Exception
     *
     * @return PermissionStoragePluginInterface
     */
    protected function getPermissionStoragePlugin()
    {
        throw new \Exception('Please set a permission storage plugin, 
        implementation of \Spryker\Zed\Permission\Communication\Plugin\PermissionStoragePluginInterface');
    }
}
