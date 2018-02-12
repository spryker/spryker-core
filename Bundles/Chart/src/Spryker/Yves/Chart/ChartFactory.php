<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Chart;

use Spryker\Yves\Chart\Plugin\Provider\ChartPluginProvider;
use Spryker\Yves\Kernel\AbstractFactory;

class ChartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface[]
     */
    public function getTwigFunctionPlugins()
    {
        return $this->getProvidedDependency(ChartDependencyProvider::TWIG_FUNCTION_PLUGINS);
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    public function getChartPlugins()
    {
        return $this->getProvidedDependency(ChartDependencyProvider::CHART_PLUGINS);
    }

    /**
     * @return \Spryker\Yves\Chart\Plugin\Provider\ChartPluginProviderInterface
     */
    public function createChartPluginProvider()
    {
        return new ChartPluginProvider(
            $this->getChartPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    public function createChartPluginCollection()
    {
        return $this->createChartPluginProvider()
            ->getChartPluginCollection();
    }
}
