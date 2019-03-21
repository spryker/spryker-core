<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SessionRedis;

use SessionHandlerInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\KeyGenerator\LockKeyGeneratorInterface;
use Spryker\Shared\SessionRedis\Handler\KeyGenerator\Redis\RedisLockKeyGenerator;
use Spryker\Shared\SessionRedis\Handler\KeyGenerator\Redis\RedisSessionKeyGenerator;
use Spryker\Shared\SessionRedis\Handler\KeyGenerator\SessionKeyGeneratorInterface;
use Spryker\Shared\SessionRedis\Handler\Lock\Redis\RedisSpinLockLocker;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedis;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisLocking;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapper;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

/**
 * @method \Spryker\Client\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisFactory extends AbstractFactory
{
    /**
     * @return \SessionHandlerInterface
     */
    public function createSessionRedisHandler(): SessionHandlerInterface
    {
        return new SessionHandlerRedis(
            $this->createSessionRedisWrapper(),
            $this->getConfig()->getSessionLifetime(),
            $this->getMonitoringService()
        );
    }

    /**
     * @return \SessionHandlerInterface
     */
    public function createSessionHandlerRedisLocking(): SessionHandlerInterface
    {
        return new SessionHandlerRedisLocking(
            $this->createSessionRedisWrapper(),
            $this->createRedisSpinLockLocker(),
            $this->createRedisSessionKeyGenerator(),
            $this->getConfig()->getSessionLifetime()
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface
     */
    public function createRedisSpinLockLocker(): SessionLockerInterface
    {
        return new RedisSpinLockLocker(
            $this->createSessionRedisWrapper(),
            $this->createRedisLockKeyGenerator(),
            $this->getConfig()->getLockingTimeout(),
            $this->getConfig()->getLockingRetryDelay(),
            $this->getConfig()->getLockingLockTtl()
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\KeyGenerator\LockKeyGeneratorInterface
     */
    public function createRedisLockKeyGenerator(): LockKeyGeneratorInterface
    {
        return new RedisLockKeyGenerator(
            $this->createRedisSessionKeyGenerator()
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
     * @return \Spryker\Shared\SessionRedis\Handler\KeyGenerator\SessionKeyGeneratorInterface
     */
    public function createRedisSessionKeyGenerator(): SessionKeyGeneratorInterface
    {
        return new RedisSessionKeyGenerator();
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
