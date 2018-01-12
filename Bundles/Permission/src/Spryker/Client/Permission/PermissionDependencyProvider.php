<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Permission\Dependency\Client\PermissionToCustomerClientBridge;

class PermissionDependencyProvider extends AbstractDependencyProvider
{
    const PLUGINS_PERMISSION = 'PLUGINS_PERMISSION';
    const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
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
    protected function addPermissionPlugins(Container $container)
    {
        $container[static::PLUGINS_PERMISSION] = function (Container $container) {
            return $this->getPermissionPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\Permission\Plugin\PermissionPluginInterface[]
     */
    protected function getPermissionPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerClient(Container $container)
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new PermissionToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }
}
