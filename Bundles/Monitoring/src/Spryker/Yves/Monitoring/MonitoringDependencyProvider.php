<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceBridge;

class MonitoringDependencyProvider extends AbstractBundleDependencyProvider
{
    const MONITORING_PLUGINS = 'monitoring plugins';
    const SERVICE_NETWORK = 'util network service';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addMonitoringPlugins($container);
        $container = $this->addUtilNetworkService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMonitoringPlugins(Container $container)
    {
        $container[static::MONITORING_PLUGINS] = function () {
            return $this->getMonitoringPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Yves\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface[]
     */
    protected function getMonitoringPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilNetworkService(Container $container)
    {
        $container[static::SERVICE_NETWORK] = function (Container $container) {
            $monitoringToUtilNetworkServiceBridge = new MonitoringToUtilNetworkServiceBridge(
                $container->getLocator()->utilNetwork()->service()
            );

            return $monitoringToUtilNetworkServiceBridge;
        };

        return $container;
    }
}
