<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication;

use Spryker\Shared\Chart\ChartPluginResolver\ChartPluginResolver;
use Spryker\Shared\Chart\ChartPluginResolver\ChartPluginResolverInterface;
use Spryker\Zed\Chart\ChartDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Chart\ChartConfig getConfig()
 * @method \Spryker\Zed\Chart\Business\ChartFacadeInterface getFacade()
 */
class ChartCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Chart\ChartPluginResolver\ChartPluginResolverInterface
     */
    public function createChartPluginCollection(): ChartPluginResolverInterface
    {
        return new ChartPluginResolver(
            $this->getChartTwigPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\ChartExtension\Dependency\Plugin\ChartPluginInterface[]
     */
    public function getChartTwigPlugins(): array
    {
        return $this->getProvidedDependency(ChartDependencyProvider::PLUGIN_TWIG_CHARTS);
    }

    /**
     * @return \Spryker\Shared\ChartExtension\Dependency\Plugin\ChartTwigFunctionPluginInterface[]
     */
    public function getChartTwigFunctionPlugins(): array
    {
        return $this->getProvidedDependency(ChartDependencyProvider::PLUGIN_CHART_TWIG_FUNCTIONS);
    }
}
