<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler;

use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilder;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionSpinLockLocker;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

class SessionHandlerFactory implements SessionHandlerFactoryInterface
{
    /**
     * @var \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var int
     */
    protected $lockingTimeoutMilliseconds;

    /**
     * @var int
     */
    protected $lockingRetryDelayMilliseconds;

    /**
     * @var int
     */
    protected $lockingLockTtlMilliseconds;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface
     */
    protected $sessionRedisLifeTimeCalculator;

    /**
     * @param \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface $monitoringService
     * @param \Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface $sessionRedisLifeTimeCalculator
     * @param int $lockingTimeoutMilliseconds
     * @param int $lockingRetryDelayMilliseconds
     * @param int $lockingLockTtlMilliseconds
     */
    public function __construct(
        SessionRedisToMonitoringServiceInterface $monitoringService,
        SessionRedisLifeTimeCalculatorInterface $sessionRedisLifeTimeCalculator,
        int $lockingTimeoutMilliseconds,
        int $lockingRetryDelayMilliseconds,
        int $lockingLockTtlMilliseconds
    ) {
        $this->monitoringService = $monitoringService;
        $this->sessionRedisLifeTimeCalculator = $sessionRedisLifeTimeCalculator;
        $this->lockingTimeoutMilliseconds = $lockingTimeoutMilliseconds;
        $this->lockingRetryDelayMilliseconds = $lockingRetryDelayMilliseconds;
        $this->lockingLockTtlMilliseconds = $lockingLockTtlMilliseconds;
    }

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     *
     * @return \SessionHandlerInterface
     */
    public function createSessionRedisHandler(SessionRedisWrapperInterface $redisClient): SessionHandlerInterface
    {
        return new SessionHandlerRedis(
            $redisClient,
            $this->createSessionKeyBuilder(),
            $this->monitoringService,
            $this->sessionRedisLifeTimeCalculator
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
            $this->createSessionSpinLockLocker($redisClient),
            $this->createSessionKeyBuilder(),
            $this->sessionRedisLifeTimeCalculator
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
            $this->lockingTimeoutMilliseconds,
            $this->lockingRetryDelayMilliseconds,
            $this->lockingLockTtlMilliseconds
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
