<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring;

use Spryker\Shared\Monitoring\Monitoring;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Monitoring\Plugin\ControllerListener;

/**
 * @method \Spryker\Yves\Monitoring\MonitoringConfig getConfig()
 */
class MonitoringFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Monitoring\Plugin\ControllerListener
     */
    public function createControllerListener()
    {
        return new ControllerListener(
            $this->createMonitoring(),
            $this->getSystem(),
            $this->getConfig()->getIgnorableTransactionRouteNames()
        );
    }

    /**
     * @return \Spryker\Shared\MonitoringExtension\MonitoringInterface
     */
    public function createMonitoring()
    {
        return new Monitoring(
            $this->getMonitoringExtensionPlugins()
        );
    }

    /**
     * @return \Spryker\Yves\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface[]
     */
    public function getMonitoringExtensionPlugins(): array
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::MONITORING_PLUGINS);
    }

    /**
     * @return \Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    public function getSystem()
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::SERVICE_NETWORK);
    }
}
