<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis\Communication;

use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactory;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapper;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReader;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReaderInterface;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReleaser;
use Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReleaserInterface;
use Spryker\Zed\SessionRedis\SessionRedisDependencyProvider;

/**
 * @method \Spryker\Zed\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SessionHandlerInterface
     */
    public function createSessionRedisHandler(): SessionHandlerInterface
    {
        return $this->createSessionHandlerFactory()->createSessionRedisHandler(
            $this->createZedSessionRedisWrapper()
        );
    }

    /**
     * @return \SessionHandlerInterface
     */
    public function createSessionHandlerRedisLocking(): SessionHandlerInterface
    {
        return $this->createSessionHandlerFactory()->createSessionHandlerRedisLocking(
            $this->createZedSessionRedisWrapper()
        );
    }

    /**
     * @return \Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReleaserInterface
     */
    public function createZedSessionLockReleaser(): SessionLockReleaserInterface
    {
        $redisClient = $this->createZedSessionRedisWrapper();

        return new SessionLockReleaser(
            $this->createSessionHandlerFactory()->createSessionSpinLockLocker($redisClient),
            $this->createRedisSessionLockReader($redisClient)
        );
    }

    /**
     * @return \Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReleaserInterface
     */
    public function createYvesSessionLockReleaser(): SessionLockReleaserInterface
    {
        $redisClient = $this->createYvesSessionRedisWrapper();

        return new SessionLockReleaser(
            $this->createSessionHandlerFactory()->createSessionSpinLockLocker($redisClient),
            $this->createRedisSessionLockReader($redisClient)
        );
    }

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     *
     * @return \Spryker\Zed\SessionRedis\Communication\Lock\SessionLockReaderInterface
     */
    public function createRedisSessionLockReader(SessionRedisWrapperInterface $redisClient): SessionLockReaderInterface
    {
        return new SessionLockReader(
            $redisClient,
            $this->createSessionHandlerFactory()->createSessionKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    public function createZedSessionRedisWrapper(): SessionRedisWrapperInterface
    {
        return new SessionRedisWrapper(
            $this->getRedisClient(),
            $this->getConfig()->getZedRedisConnectionKey(),
            $this->getConfig()->getZedRedisConnectionConfiguration()
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    public function createYvesSessionRedisWrapper(): SessionRedisWrapperInterface
    {
        return new SessionRedisWrapper(
            $this->getRedisClient(),
            $this->getConfig()->getYvesRedisConnectionKey(),
            $this->getConfig()->getYvesRedisConnectionConfiguration()
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface
     */
    public function createSessionHandlerFactory(): SessionHandlerFactoryInterface
    {
        return new SessionHandlerFactory(
            $this->getMonitoringService(),
            $this->getConfig()->getZedSessionLifeTime(),
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
        return $this->getProvidedDependency(SessionRedisDependencyProvider::CLIENT_SESSION_REDIS);
    }
}
