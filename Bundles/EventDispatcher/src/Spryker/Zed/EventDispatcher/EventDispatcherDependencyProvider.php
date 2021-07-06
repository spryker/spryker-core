<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventDispatcher;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\EventDispatcher\EventDispatcherConfig getConfig()
 */
class EventDispatcherDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_EVENT_DISPATCHER_PLUGINS = 'PLUGINS_EVENT_DISPATCHER_PLUGINS';
    public const PLUGINS_BACKEND_GATEWAY_EVENT_DISPATCHER_PLUGINS = 'PLUGINS_BACKEND_GATEWAY_EVENT_DISPATCHER_PLUGINS';
    public const PLUGINS_BACKEND_API_EVENT_DISPATCHER_PLUGINS = 'PLUGINS_BACKEND_API_EVENT_DISPATCHER_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addEventDispatcherPlugins($container);
        $container = $this->addBackendGatewayEventDispatcherPlugins($container);
        $container = $this->addBackendApiEventDispatcherPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventDispatcherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_EVENT_DISPATCHER_PLUGINS, function (Container $container) {
            return $this->getEventDispatcherPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface[]
     */
    protected function getEventDispatcherPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBackendGatewayEventDispatcherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_BACKEND_GATEWAY_EVENT_DISPATCHER_PLUGINS, function (Container $container) {
            return $this->getBackendGatewayEventDispatcherPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface[]
     */
    protected function getBackendGatewayEventDispatcherPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBackendApiEventDispatcherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_BACKEND_API_EVENT_DISPATCHER_PLUGINS, function (Container $container) {
            return $this->getBackendApiEventDispatcherPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface[]
     */
    protected function getBackendApiEventDispatcherPlugins(): array
    {
        return [];
    }
}
