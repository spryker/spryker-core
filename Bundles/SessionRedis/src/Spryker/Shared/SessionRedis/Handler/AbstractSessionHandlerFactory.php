<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler;

use SessionHandlerInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface;
use Spryker\Shared\SessionRedis\Handler\Lock\SpinLockLocker;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Shared\SessionRedis\SessionRedisConstants;

abstract class AbstractSessionHandlerFactory
{
    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     *
     * @return \SessionHandlerInterface
     */
    public function createSessionRedisHandler(SessionRedisWrapperInterface $redisClient): SessionHandlerInterface
    {
        return new SessionHandlerRedis(
            $redisClient,
            $this->getSessionLifetime(),
            $this->getMonitoringService()
        );
    }

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     *
     * @return \SessionHandlerInterface
     */
    public function createSessionHandlerRedisLocking(SessionRedisWrapperInterface $redisClient): SessionHandlerInterface
    {
        return new SessionHandlerRedisLocking(
            $redisClient,
            $this->createSpinLockLocker($redisClient),
            $this->createSessionKeyBuilder(),
            $this->getSessionLifetime()
        );
    }

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     *
     * @return \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface
     */
    public function createSpinLockLocker(SessionRedisWrapperInterface $redisClient): SessionLockerInterface
    {
        return new SpinLockLocker(
            $redisClient,
            $this->createSessionKeyBuilder(),
            Config::get(SessionRedisConstants::LOCKING_TIMEOUT_MILLISECONDS, 0),
            Config::get(SessionRedisConstants::LOCKING_RETRY_DELAY_MICROSECONDS, 0),
            Config::get(SessionRedisConstants::LOCKING_LOCK_TTL_MILLISECONDS, 0)
        );
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface
     */
    public function createSessionKeyBuilder(): SessionKeyBuilderInterface
    {
        return new SessionKeyBuilder();
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface
     */
    abstract protected function getMonitoringService(): SessionRedisToMonitoringServiceInterface;

    /**
     * @return int
     */
    abstract protected function getSessionLifetime(): int;
}
