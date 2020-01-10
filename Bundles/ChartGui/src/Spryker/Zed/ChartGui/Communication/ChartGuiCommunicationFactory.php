<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ChartGui\Communication;

use Spryker\Zed\ChartGui\ChartGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ChartGui\ChartGuiConfig getConfig()
 * @method \Spryker\Zed\ChartGui\Business\ChartGuiFacadeInterface getFacade()
 */
class ChartGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface[]
     */
    public function getTwigChartFunctionPlugins(): array
    {
        return $this->getProvidedDependency(ChartGuiDependencyProvider::PLUGIN_TWIG_CHART_GUI_FUNCTIONS);
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    public function getChartPlugins(): array
    {
        return $this->getProvidedDependency(ChartGuiDependencyProvider::PLUGIN_CHART);
    }
}
