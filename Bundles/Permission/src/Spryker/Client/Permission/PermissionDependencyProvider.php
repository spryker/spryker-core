<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission;

use Exception;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Permission\Dependency\Plugin\PermissionStoragePluginInterface;

class PermissionDependencyProvider extends AbstractDependencyProvider
{
    const PLUGINS_PERMISSION = 'PLUGINS_PERMISSION';
    const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const PLUGIN_PERMISSION_STORAGE = 'PLUGIN_PERMISSION_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addPermissionPlugins($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPermissionPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PERMISSION] = function (Container $container) {
            return $this->getPermissionPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Permission\Plugin\PermissionPluginInterface[]
     */
    protected function getPermissionPlugins(): array
    {
        return [];
    }

    /**
     * @throws \Exception
     *
     * @return \Spryker\Client\Permission\Dependency\Plugin\PermissionStoragePluginInterface
     */
    protected function getPermissionStoragePlugin(): PermissionStoragePluginInterface
    {
        throw new Exception('Please set a permission storage plugin, implementation of 
        \Spryker\Client\Permission\Dependency\Plugin\PermissionStoragePluginInterface');
    }
}
