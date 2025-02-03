<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Monitoring;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\Monitoring\Dependency\Client\MonitoringToLocaleClientInterface;
use Spryker\Glue\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface;
use Spryker\Glue\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Spryker\Service\Monitoring\MonitoringServiceInterface;

class MonitoringFactory extends AbstractBackendApiFactory
{
    /**
     * @return \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    public function getMonitoringService(): MonitoringServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::SERVICE_MONITORING);
    }

    /**
     * @return \Spryker\Glue\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    public function getUtilNetworkService(): MonitoringToUtilNetworkServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::SERVICE_UTIL_NETWORK);
    }

    /**
     * @return \Spryker\Glue\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MonitoringToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Glue\Monitoring\Dependency\Client\MonitoringToLocaleClientInterface
     */
    public function getLocaleClient(): MonitoringToLocaleClientInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::CLIENT_LOCALE);
    }
}
