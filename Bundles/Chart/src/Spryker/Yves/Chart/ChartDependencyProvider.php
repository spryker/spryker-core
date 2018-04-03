<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Chart;

use Spryker\Yves\Chart\Plugin\Twig\TwigBarChartPlugin;
use Spryker\Yves\Chart\Plugin\Twig\TwigChartPlugin;
use Spryker\Yves\Chart\Plugin\Twig\TwigLineChartPlugin;
use Spryker\Yves\Chart\Plugin\Twig\TwigPieChartPlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ChartDependencyProvider extends AbstractBundleDependencyProvider
{
    const TWIG_CHART_FUNCTION_PLUGINS = 'TWIG_CHART_FUNCTION_PLUGINS';
    const CHART_PLUGINS = 'CHART_PLUGINS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addTwigChartFunctionPlugins($container);
        $container = $this->addChartPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addTwigChartFunctionPlugins(Container $container)
    {
        $container[static::TWIG_CHART_FUNCTION_PLUGINS] = function () {
            return $this->getTwigChartFunctionPlugins();
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
     * @return \Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface[]
     */
    protected function getTwigChartFunctionPlugins()
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
