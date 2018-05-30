<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Communication;

use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Monitoring\Communication\Plugin\ControllerListener;
use Spryker\Zed\Monitoring\Communication\Plugin\GatewayControllerListener;
use Spryker\Zed\Monitoring\MonitoringDependencyProvider;

/**
 * @method \Spryker\Zed\Monitoring\MonitoringConfig getConfig()
 */
class MonitoringCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Monitoring\Communication\Plugin\GatewayControllerListener
     */
    public function createGatewayControllerListener()
    {
        return new GatewayControllerListener(
            $this->getMonitoringService()
        );
    }

    /**
     * @return \Spryker\Zed\Monitoring\Communication\Plugin\ControllerListener
     */
    public function createControllerListener()
    {
        return new ControllerListener(
            $this->getMonitoringService(),
            $this->getStoreFacade(),
            $this->getLocaleFacade(),
            $this->getUtilNetworkService(),
            $this->getConfig()->getIgnorableTransactions()
        );
    }

    /**
     * @return \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    public function getMonitoringService(): MonitoringServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::MONITORING_SERVICE);
    }

    /**
     * @return \Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToStoreFacadeInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    public function getUtilNetworkService()
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::SERVICE_UTIL_NETWORK);
    }
}
