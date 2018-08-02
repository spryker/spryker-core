<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication;

use Spryker\Shared\Chart\ChartPluginCollection\ChartPluginCollection;
use Spryker\Shared\Chart\ChartPluginCollection\ChartPluginCollectionInterface;
use Spryker\Zed\Chart\ChartDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Chart\ChartConfig getConfig()
 */
class ChartCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface[]
     */
    public function getTwigChartFunctionPlugins(): array
    {
        return $this->getProvidedDependency(ChartDependencyProvider::PLUGIN_TWIG_CHART_FUNCTIONS);
    }

    /**
     * @return \Spryker\Shared\Chart\ChartPluginCollection\ChartPluginCollectionInterface
     */
    public function createChartPluginCollection(): ChartPluginCollectionInterface
    {
        return new ChartPluginCollection(
            $this->getChartPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    public function getChartPlugins(): array
    {
        return $this->getProvidedDependency(ChartDependencyProvider::PLUGIN_CHARTS);
    }
}
