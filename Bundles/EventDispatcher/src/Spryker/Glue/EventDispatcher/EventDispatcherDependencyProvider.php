<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EventDispatcher;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class EventDispatcherDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_EVENT_DISPATCHER = 'PLUGINS_EVENT_DISPATCHER_PLUGINS';

    /**
     * @var string
     */
    public const PLUGINS_BACKEND_EVENT_DISPATCHER = 'PLUGINS_BACKEND_EVENT_DISPATCHER';

    /**
     * @var string
     */
    public const PLUGINS_STOREFRONT_EVENT_DISPATCHER = 'PLUGINS_STOREFRONT_EVENT_DISPATCHER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addEventDispatcherPlugins($container);
        $container = $this->addBackendEventDispatcherPlugins($container);
        $container = $this->addStoreforntEventDispatcherPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addEventDispatcherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_EVENT_DISPATCHER, function (Container $container) {
            return $this->getEventDispatcherPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addBackendEventDispatcherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_BACKEND_EVENT_DISPATCHER, function (Container $container) {
            return $this->getBackendEventDispatcherPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreforntEventDispatcherPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STOREFRONT_EVENT_DISPATCHER, function (Container $container) {
            return $this->getStorefrontEventDispatcherPlugins();
        });

        return $container;
    }

    /**
     * The stack of plugins utilized to extend the event dispatcher for Glue.
     *
     * @return array<\Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface>
     */
    protected function getEventDispatcherPlugins(): array
    {
        return [];
    }

    /**
     * The stack of plugins utilized to extend the event dispatcher for Glue Backend.
     *
     * @return array<\Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface>
     */
    protected function getBackendEventDispatcherPlugins(): array
    {
        return [];
    }

    /**
     * The stack of plugins utilized to extend the event dispatcher for Glue Storefront.
     *
     * @return array<\Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface>
     */
    protected function getStorefrontEventDispatcherPlugins(): array
    {
        return [];
    }
}
