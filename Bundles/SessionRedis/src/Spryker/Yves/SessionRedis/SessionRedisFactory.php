<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis;

use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactory;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapper;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisFactory extends AbstractFactory
{
    /**
     * @return \SessionHandlerInterface
     */
    public function createSessionRedisHandler(): SessionHandlerInterface
    {
        return $this->createSessionHandlerFactory()->createSessionRedisHandler(
            $this->createSessionRedisWrapper()
        );
    }

    /**
     * @return \SessionHandlerInterface
     */
    public function createSessionHandlerRedisLocking(): SessionHandlerInterface
    {
        return $this->createSessionHandlerFactory()->createSessionHandlerRedisLocking(
            $this->createSessionRedisWrapper()
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    public function createSessionRedisWrapper(): SessionRedisWrapperInterface
    {
        return new SessionRedisWrapper(
            $this->getRedisClient(),
            SessionRedisConfig::SESSION_REDIS_CONNECTION_KEY,
            $this->getConfig()->getRedisConnectionConfiguration()
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface
     */
    public function createSessionHandlerFactory(): SessionHandlerFactoryInterface
    {
        return new SessionHandlerFactory(
            $this->getMonitoringService(),
            $this->getConfig()->getSessionLifetime(),
            $this->getConfig()->getLockingTimeoutMilliseconds(),
            $this->getConfig()->getLockingRetryDelayMicroseconds(),
            $this->getConfig()->getLockingLockTtlMilliseconds()
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface
     */
    public function getMonitoringService(): SessionRedisToMonitoringServiceInterface
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::SERVICE_MONITORING);
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface
     */
    public function getRedisClient(): SessionRedisToRedisClientInterface
    {
        return $this->getProvidedDependency(SessionRedisDependencyProvider::CLIENT_REDIS);
    }
}
