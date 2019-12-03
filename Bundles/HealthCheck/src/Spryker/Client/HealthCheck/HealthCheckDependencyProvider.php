<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * @method \Spryker\Client\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_HEALTH_CHECK = 'SERVICE_HEALTH_CHECK';

    public const PLUGINS_HEALTH_CHECK = 'PLUGINS_HEALTH_CHECK';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addHealthCheckService($container);
        $container = $this->addHealthCheckPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addHealthCheckService(Container $container): Container
    {
        $container->set(static::SERVICE_HEALTH_CHECK, function (Container $container) {
            return $container->getLocator()->healthCheck()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addHealthCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_HEALTH_CHECK, function (Container $container) {
            return $this->getHealthCheckPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getHealthCheckPlugins(): array
    {
        return [];
    }
}
