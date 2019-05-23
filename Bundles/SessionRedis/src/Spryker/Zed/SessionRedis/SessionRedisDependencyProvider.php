<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis;

use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientBridge;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_MONITORING = 'SERVICE_MONITORING';
    public const CLIENT_SESSION_REDIS = 'CLIENT_SESSION_REDIS';
    public const PLUGINS_HANDLER_SESSION = 'PLUGINS_HANDLER_SESSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addRedisClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addRedisClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMonitoringService(Container $container): Container
    {
        $container->set(static::SERVICE_MONITORING, function (Container $container) {
            $sessionToMonitoringServiceBridge = new SessionRedisToMonitoringServiceBridge(
                $container->getLocator()->monitoring()->service()
            );

            return $sessionToMonitoringServiceBridge;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRedisClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION_REDIS, function (Container $container) {
            return new SessionRedisToRedisClientBridge(
                $container->getLocator()->redis()->client()
            );
        });

        return $container;
    }
}
