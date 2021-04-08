<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis;

use Spryker\Shared\Kernel\ContainerInterface;
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
    public const PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR = 'PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR';
    public const REQUEST_STACK = 'REQUEST_STACK';

    /**
     * @deprecated Use {@link \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK} instead.
     */
    protected const REQUEST_STACK_CONTAINER_KEY = 'request_stack';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addRedisClient($container);
        $container = $this->addRequestStack($container);
        $container = $this->addSessionRedisLifeTimeCalculatorPlugins($container);

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
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::REQUEST_STACK, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionRedisLifeTimeCalculatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SESSION_REDIS_LIFE_TIME_CALCULATOR, function () {
            return $this->getSessionRedisLifeTimeCalculatorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface[]
     */
    protected function getSessionRedisLifeTimeCalculatorPlugins(): array
    {
        return [];
    }
}
