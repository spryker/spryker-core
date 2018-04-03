<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication;

use Spryker\Shared\Chart\ChartPluginCollection\ChartPluginCollection;
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
    public function getTwigChartFunctionPlugins()
    {
        return $this->getProvidedDependency(ChartDependencyProvider::TWIG_CHART_FUNCTION_PLUGINS);
    }

    /**
     * @return \Spryker\Shared\Chart\ChartPluginCollection\ChartPluginCollectionInterface
     */
    public function createChartPluginCollection()
    {
        return new ChartPluginCollection(
            $this->getChartPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    protected function getChartPlugins()
    {
        return $this->getProvidedDependency(ChartDependencyProvider::CHART_PLUGINS);
    }
}
