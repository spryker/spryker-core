<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart;

use Spryker\Zed\Chart\Communication\Plugin\Twig\TwigBarChartPlugin;
use Spryker\Zed\Chart\Communication\Plugin\Twig\TwigChartPlugin;
use Spryker\Zed\Chart\Communication\Plugin\Twig\TwigLineChartPlugin;
use Spryker\Zed\Chart\Communication\Plugin\Twig\TwigPieChartPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ChartDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_TWIG_CHART_FUNCTIONS = 'PLUGIN_TWIG_CHART_FUNCTIONS';
    public const PLUGIN_CHARTS = 'PLUGIN_CHARTS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addTwigChartFunctionPlugins($container);
        $container = $this->addChartPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigChartFunctionPlugins(Container $container): Container
    {
        $container[static::PLUGIN_TWIG_CHART_FUNCTIONS] = function () {
            return $this->getTwigChartFunctionPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addChartPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CHARTS] = function () {
            return $this->getChartPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface[]
     */
    protected function getTwigChartFunctionPlugins(): array
    {
        return [
            new TwigPieChartPlugin(),
            new TwigBarChartPlugin(),
            new TwigLineChartPlugin(),
            new TwigChartPlugin(),
        ];
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    protected function getChartPlugins(): array
    {
        return [];
    }
}
