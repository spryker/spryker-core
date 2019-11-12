<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ZedRequest;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\ZedRequest\Dependency\Client\ZedRequestToHealthCheckClientBridge;

class ZedRequestDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_HEALTH_CHECK = 'CLIENT_HEALTH_CHECK';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = $this->addHealthCheckClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addHealthCheckClient(Container $container): Container
    {
        $container->set(static::CLIENT_HEALTH_CHECK, function ($container) {
            return new ZedRequestToHealthCheckClientBridge(
                $container->getLocator()->healthCheck()->client()
            );
        });

        return $container;
    }
}
