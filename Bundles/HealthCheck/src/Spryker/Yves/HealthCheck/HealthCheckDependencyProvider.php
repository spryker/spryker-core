<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\HealthCheck;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \Spryker\Yves\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_HEALTH_CHECK = 'SERVICE_HEALTH_CHECK';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addHealthCheckService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addHealthCheckService(Container $container): Container
    {
        $container->set(static::SERVICE_HEALTH_CHECK, function (Container $container) {
            return $container->getLocator()->healthCheck()->service();
        });

        return $container;
    }
}
