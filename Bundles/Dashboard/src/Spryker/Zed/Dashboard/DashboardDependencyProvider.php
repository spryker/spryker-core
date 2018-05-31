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
    public const PLUGIN_CHART_NAMES = 'PLUGIN_CHART_NAMES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addChartPlugins($container);

        return $container;
    }
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addChartPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CHART_NAMES] = function () {
            return $this->getPluginChartNames();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    protected function getPluginChartNames(): array
    {
        return [];
    }
}
