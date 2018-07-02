<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dashboard;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DashboardDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_RENDER_DASHBOARD = 'PLUGIN_RENDER_DASHBOARD';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addRenderDashboardPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRenderDashboardPlugins(Container $container): Container
    {
        $container[static::PLUGIN_RENDER_DASHBOARD] = function () {
            return $this->getDashboardPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Dashboard\Dependency\Plugin\DashboardPluginInterface[]
     */
    protected function getDashboardPlugins(): array
    {
        return [];
    }
}
