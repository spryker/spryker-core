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
    public const PLUGINS_EVENT_DISPATCHER_EXTENSIONS = 'PLUGINS_EVENT_DISPATCHER_EXTENSIONS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventDispatcherExtensionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventDispatcherExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_EVENT_DISPATCHER_EXTENSIONS, function (Container $container) {
            return $this->getEventDispatcherExtensionPlugins();
        });

        return $container;
    }

    /**
     * @return array
     */
    protected function getEventDispatcherExtensionPlugins(): array
    {
        return [];
    }
}
