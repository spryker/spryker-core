<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Router;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\Router\RouterConfig getConfig()
 */
class RouterDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_ROUTER = 'PLUGINS_ROUTER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addRouterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRouterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ROUTER, function () {
            return $this->getRouterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Glue\RouterExtension\Dependency\Plugin\RouterPluginInterface[]
     */
    protected function getRouterPlugins(): array
    {
        return [];
    }
}
