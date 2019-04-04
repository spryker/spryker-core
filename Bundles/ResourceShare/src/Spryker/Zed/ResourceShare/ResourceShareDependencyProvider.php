<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ResourceShare\ResourceShareConfig getConfig()
 */
class ResourceShareDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_RESOURCE_SHARE_ACTIVATOR_STRATEGY = 'PLUGINS_RESOURCE_SHARE_ACTIVATOR_STRATEGY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addResourceShareActivatorStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addResourceShareActivatorStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_RESOURCE_SHARE_ACTIVATOR_STRATEGY] = function () {
            return $this->getResourceShareActivatorStrategyPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected function getResourceShareActivatorStrategyPlugins(): array
    {
        return [];
    }
}
