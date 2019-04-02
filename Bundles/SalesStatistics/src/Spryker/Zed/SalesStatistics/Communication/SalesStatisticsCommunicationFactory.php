<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesStatistics\SalesStatisticsDependencyProvider;
use Twig\Environment;

/**
 * @method \Spryker\Zed\SalesStatistics\SalesStatisticsConfig getConfig()
 * @method \Spryker\Zed\SalesStatistics\Persistence\SalesStatisticsRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesStatistics\Business\SalesStatisticsFacadeInterface getFacade()
 */
class SalesStatisticsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(SalesStatisticsDependencyProvider::RENDERER);
    }
}
