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
    public const MONITORING_SERVICE = 'monitoring service';
    public const SERVICE_NETWORK = 'util network service';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addUtilNetworkService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMonitoringService(Container $container): Container
    {
        $container[static::MONITORING_SERVICE] = function (Container $container) {
            return $container->getLocator()->monitoring()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilNetworkService(Container $container): Container
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
