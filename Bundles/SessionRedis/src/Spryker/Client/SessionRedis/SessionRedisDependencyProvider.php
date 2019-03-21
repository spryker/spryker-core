<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SessionRedis;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientBridge;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceBridge;

/**
 * @method \Spryker\Client\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_MONITORING = 'SERVICE_MONITORING';
    public const CLIENT_REDIS = 'CLIENT_REDIS';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addRedisClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addRedisClient(Container $container): Container
    {
        $container[static::CLIENT_REDIS] = function (Container $container) {
            return new SessionRedisToRedisClientBridge(
                $container->getLocator()->redis()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMonitoringService(Container $container): Container
    {
        $container[static::SERVICE_MONITORING] = function () use ($container) {
            return new SessionRedisToMonitoringServiceBridge(
                $container->getLocator()->monitoring()->service()
            );
        };

        return $container;
    }
}
