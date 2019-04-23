<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Router\RouterConfig getConfig()
 */
class RouterDependencyProvider extends AbstractBundleDependencyProvider
{
    public const ROUTER_PLUGINS = 'router-plugins';
    public const ROUTER_ENHANCER_PLUGINS = 'router enhancer plugin';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addRouterPlugins($container);
        $container = $this->addRouterEnhancerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRouterPlugins(Container $container): Container
    {
        $container->set(static::ROUTER_PLUGINS, function () {
            return $this->getRouterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterPluginInterface[]
     */
    protected function getRouterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRouterEnhancerPlugins(Container $container): Container
    {
        $container->set(static::ROUTER_ENHANCER_PLUGINS, function () {
            return $this->getRouterEnhancerPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterPluginInterface[]
     */
    protected function getRouterEnhancerPlugins(): array
    {
        return [];
    }
}
