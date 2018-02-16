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
    const CHART_TWIG_FUNCTIONS = 'CHART_TWIG_FUNCTIONS';
    const CHART_PLUGINS = 'CHART_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addTwigFunctions($container);
        $container = $this->addChartPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTwigFunctions(Container $container)
    {
        $container[static::CHART_TWIG_FUNCTIONS] = function () {
            return $this->getTwigFunctions();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addChartPlugins(Container $container)
    {
        $container[static::CHART_PLUGINS] = function () {
            return $this->getChartPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\TwigFunctionPluginInterface[]
     */
    protected function getTwigFunctions()
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
    protected function getChartPlugins()
    {
        return [];
    }
}
