<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ChartGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ChartGui\ChartGuiConfig getConfig()
 */
class ChartGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_TWIG_CHART_GUI_FUNCTIONS = 'PLUGIN_TWIG_CHART_GUI_FUNCTIONS';
    public const PLUGIN_CHART = 'PLUGIN_CHART';

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
        $container->set(static::PLUGIN_TWIG_CHART_GUI_FUNCTIONS, function () {
            return $this->getTwigChartFunctionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addChartPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CHART, function () {
            return $this->getChartPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface[]
     */
    protected function getTwigChartFunctionPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    protected function getChartPlugins(): array
    {
        return [];
    }
}
