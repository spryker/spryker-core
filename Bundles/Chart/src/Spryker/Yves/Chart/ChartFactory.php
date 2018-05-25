<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Chart;

use Spryker\Shared\Chart\ChartPluginCollection\ChartPluginCollection;
use Spryker\Shared\Chart\ChartPluginCollection\ChartPluginCollectionInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class ChartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface[]
     */
    public function getTwigChartFunctionPlugins(): array
    {
        return $this->getProvidedDependency(ChartDependencyProvider::TWIG_CHART_FUNCTION_PLUGINS);
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
    protected function getChartPlugins(): array
    {
        return $this->getProvidedDependency(ChartDependencyProvider::CHART_PLUGINS);
    }
}
