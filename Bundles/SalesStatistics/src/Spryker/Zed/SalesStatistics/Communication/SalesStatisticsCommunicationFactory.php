<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesStatistics\SalesStatisticsDependencyProvider;
use Twig_Environment;

/**
 * @method \Spryker\Zed\SalesStatistics\SalesStatisticsConfig getConfig()
 */
class SalesStatisticsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Twig_Environment
     */
    public function getTwigEnvironment(): Twig_Environment
    {
        return $this->getProvidedDependency(SalesStatisticsDependencyProvider::RENDERER);
    }
}
