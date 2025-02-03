<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Monitoring;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\Monitoring\Dependency\Client\MonitoringToLocaleClientBridge;
use Spryker\Glue\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeBridge;
use Spryker\Glue\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceBridge;
use Spryker\Glue\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Spryker\Service\Monitoring\MonitoringServiceInterface;

class MonitoringDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_MONITORING = 'SERVICE_MONITORING';

    /**
     * @var string
     */
    public const SERVICE_UTIL_NETWORK = 'SERVICE_UTIL_NETWORK';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addMonitoringService($container);
        $container = $this->addUtilNetworkService($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addMonitoringService(Container $container): Container
    {
        $container->set(static::SERVICE_MONITORING, function (Container $container): MonitoringServiceInterface {
            return $container->getLocator()->monitoring()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addUtilNetworkService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_NETWORK, function (Container $container): MonitoringToUtilNetworkServiceInterface {
            return new MonitoringToUtilNetworkServiceBridge($container->getLocator()->utilNetwork()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new MonitoringToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new MonitoringToLocaleClientBridge($container->getLocator()->locale()->client());
        });

        return $container;
    }
}
