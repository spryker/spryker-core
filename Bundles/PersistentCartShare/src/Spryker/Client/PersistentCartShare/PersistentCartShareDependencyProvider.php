<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientBridge;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToResourceShareClientBridge;

class PersistentCartShareDependencyProvider extends AbstractDependencyProvider
{
    public const PLUGINS_CART_SHARE_OPTION = 'PLUGINS_CART_SHARE_OPTION';
    public const CLIENT_RESOURCE_SHARE = 'CLIENT_RESOURCE_SHARE';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addCartShareOptionPlugins($container);
        $container = $this->addResourceShareClient($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addResourceShareClient(Container $container): Container
    {
        $container[static::CLIENT_RESOURCE_SHARE] = function (Container $container) {
            return new PersistentCartShareToResourceShareClientBridge($container->getLocator()->resourceShare()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new PersistentCartShareToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartShareOptionPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CART_SHARE_OPTION] = function () {
            return $this->getCartShareOptionPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface[]
     */
    protected function getCartShareOptionPlugins(): array
    {
        return [];
    }
}
