<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_HEALTH_CHECK = 'SERVICE_HEALTH_CHECK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addHealthCheckService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addHealthCheckService(Container $container): Container
    {
        $container->set(static::SERVICE_HEALTH_CHECK, function (Container $container) {
            return $container->getLocator()->healthCheck()->service();
        });

        return $container;
    }
}
