<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\EventDispatcher;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class EventDispatcherDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_EVENT_DISPATCHER_EXTENSIONS = 'PLUGINS_EVENT_DISPATCHER_EXTENSIONS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addEventDispatcherExtensionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addEventDispatcherExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_EVENT_DISPATCHER_EXTENSIONS, function (Container $container) {
            return $this->getEventDispatcherExtensionPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherExtensionPluginInterface[]
     */
    protected function getEventDispatcherExtensionPlugins(): array
    {
        return [];
    }
}
