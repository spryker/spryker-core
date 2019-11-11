<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi;

use Spryker\Glue\HealthCheckRestApi\Dependency\Service\HealthCheckRestApiToHealthCheckServiceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig getConfig()
 */
class HealthCheckRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_HEALTH_CHECK = 'SERVICE_HEALTH_CHECK';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addHealthCheckService(Container $container): Container
    {
        $container->set(static::SERVICE_HEALTH_CHECK, function (Container $container) {
            return new HealthCheckRestApiToHealthCheckServiceBridge(
                $container->getLocator()->healthCheck()->service()
            );
        });

        return $container;
    }

}
