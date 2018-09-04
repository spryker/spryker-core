<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Monitoring;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Monitoring\Model\Monitoring;
use Spryker\Service\Monitoring\Model\MonitoringInterface;

class MonitoringServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Monitoring\Model\MonitoringInterface
     */
    public function createMonitoring(): MonitoringInterface
    {
        return new Monitoring(
            $this->getMonitoringExtensions()
        );
    }

    /**
     * @return \Spryker\Service\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface[]
     */
    public function getMonitoringExtensions(): array
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::MONITORING_EXTENSIONS);
    }
}
