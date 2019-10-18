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
    public const PLUGINS_EVENT_DISPATCHER_PLUGINS = 'PLUGINS_EVENT_DISPATCHER_PLUGINS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addEventDispatcherPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
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
}
