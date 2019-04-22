<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler;

use Spryker\Shared\Config\Config;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionSpinLockLocker;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Spryker\Shared\SessionRedis\SessionRedisConstants;

class SessionHandlerFactory implements SessionHandlerFactoryInterface
{
    /**
     * @var \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var int
     */
    protected $sessionLifeTime;

    /**
     * @param \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface $monitoringService
     * @param int $sessionLifeTime
     */
    public function __construct(SessionRedisToMonitoringServiceInterface $monitoringService, int $sessionLifeTime)
    {
        $this->monitoringService = $monitoringService;
        $this->sessionLifeTime = $sessionLifeTime;
    }

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     *
     * @return \Spryker\Shared\SessionRedis\Handler\SessionHandlerInterface
     */
    public function createSessionRedisHandler(SessionRedisWrapperInterface $redisClient): SessionHandlerInterface
    {
        return new SessionHandlerRedis(
            $redisClient,
            $this->createSessionKeyBuilder(),
            $this->monitoringService,
            $this->sessionLifeTime
        );
    }

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     *
     * @return \Spryker\Shared\SessionRedis\Handler\SessionHandlerInterface
     */
    public function createSessionHandlerRedisLocking(SessionRedisWrapperInterface $redisClient): SessionHandlerInterface
    {
        return new SessionHandlerRedisLocking(
            $redisClient,
            $this->createSessionSpinLockLocker($redisClient),
            $this->createSessionKeyBuilder(),
            $this->sessionLifeTime
        );
    }

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     *
     * @return \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface
     */
    public function createSessionSpinLockLocker(SessionRedisWrapperInterface $redisClient): SessionLockerInterface
    {
        return new SessionSpinLockLocker(
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
}
