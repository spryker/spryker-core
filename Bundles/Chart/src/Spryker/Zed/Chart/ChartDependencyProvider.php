<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Chart\ChartConfig getConfig()
 */
class ChartDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_TWIG_CHARTS = 'PLUGIN_TWIG_CHARTS';
    public const PLUGIN_CHART_TWIG_FUNCTIONS = 'PLUGIN_CHART_TWIG_FUNCTIONS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addChartTwigPlugins($container);
        $container = $this->addChartTwigFunctionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addChartTwigPlugins(Container $container): Container
    {
        $container[static::PLUGIN_TWIG_CHARTS] = function () {
            return $this->getChartTwigPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addChartTwigFunctionPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CHART_TWIG_FUNCTIONS] = function () {
            return $this->geChartTwigFunctionPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ChartExtension\Dependency\Plugin\ChartTwigFunctionPluginInterface[]
     */
    protected function geChartTwigFunctionPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ChartExtension\Dependency\Plugin\ChartPluginInterface[]
     */
    protected function getChartTwigPlugins(): array
    {
        return [];
    }
}
