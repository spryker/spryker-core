<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Chart;

use Spryker\Yves\Chart\Plugin\Twig\TwigBarChart;
use Spryker\Yves\Chart\Plugin\Twig\TwigGeneralChart;
use Spryker\Yves\Chart\Plugin\Twig\TwigLineChart;
use Spryker\Yves\Chart\Plugin\Twig\TwigPieChart;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ChartDependencyProvider extends AbstractBundleDependencyProvider
{
    const TWIG_FUNCTION_PLUGINS = 'TWIG_FUNCTION_PLUGINS';
    const CHART_PLUGINS = 'CHART_PLUGINS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addTwigFunctionPlugins($container);
        $container = $this->addChartPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigFunctionPlugins(Container $container)
    {
        $container[static::TWIG_FUNCTION_PLUGINS] = function () {
            return $this->getTwigFunctionPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addChartPlugins(Container $container)
    {
        $container[static::CHART_PLUGINS] = function () {
            return $this->getChartPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface[]
     */
    protected function getTwigFunctionPlugins()
    {
        return [
            new TwigPieChart(),
            new TwigBarChart(),
            new TwigLineChart(),
            new TwigGeneralChart(),
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
