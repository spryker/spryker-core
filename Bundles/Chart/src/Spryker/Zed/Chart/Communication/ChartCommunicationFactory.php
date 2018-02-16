<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Communication;

use Spryker\Zed\Chart\Business\Provider\ChartPluginCollection;
use Spryker\Zed\Chart\ChartDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Chart\ChartConfig getConfig()
 */
class ChartCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\TwigFunctionPluginInterface[]
     */
    public function getTwigFunctions()
    {
        return $this->getProvidedDependency(ChartDependencyProvider::CHART_TWIG_FUNCTIONS);
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    public function getChartPlugins()
    {
        return $this->getProvidedDependency(ChartDependencyProvider::CHART_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\Chart\Business\Provider\ChartPluginCollectionInterface
     */
    public function createChartPluginCollection()
    {
        return new ChartPluginCollection(
            $this->getChartPlugins()
        );
    }
}
