<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container as KernelContainer;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeBridge;
use Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToStoreFacadeBridge;
use Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceBridge;

class MonitoringDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MONITORING_SERVICE = 'monitoring service';
    public const FACADE_STORE = 'store facade';
    public const FACADE_LOCALE = 'locale facade';
    public const SERVICE_UTIL_NETWORK = 'util network service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addUtilNetworkService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMonitoringService(Container $container): KernelContainer
    {
        $container[static::MONITORING_SERVICE] = function (Container $container) {
            return $container->getLocator()->monitoring()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            $monitoringToStoreFacadeBridge = new MonitoringToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );

            return $monitoringToStoreFacadeBridge;
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            $monitoringToLocaleFacadeBridge = new MonitoringToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );

            return $monitoringToLocaleFacadeBridge;
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilNetworkService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_NETWORK] = function (Container $container) {
            $monitoringToUtilNetworkServiceBridge = new MonitoringToUtilNetworkServiceBridge(
                $container->getLocator()->utilNetwork()->service()
            );

            return $monitoringToUtilNetworkServiceBridge;
        };

        return $container;
    }
}
