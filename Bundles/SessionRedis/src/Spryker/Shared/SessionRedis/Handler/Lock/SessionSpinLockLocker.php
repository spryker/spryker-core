<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\Lock;

use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

class SessionSpinLockLocker implements SessionLockerInterface
{
    public const DEFAULT_TIMEOUT_MILLISECONDS = 10000;
    public const DEFAULT_RETRY_DELAY_MICROSECONDS = 10000;
    public const DEFAULT_LOCK_TTL_MILLISECONDS = 20000;

    public const LOG_METRIC_LOCK_ACQUIRE_TIME = 'Redis/Session_lock_acquire_time';
    public const LOG_METRIC_LOCK_RELEASE_TIME = 'Redis/Session_lock_release_time';
    public const LOG_METRIC_LOCK_WAIT_TIME = 'Redis/Session_lock_wait_time';

    /**
     * @var \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected $redisClient;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var int
     */
    protected $timeoutMilliseconds;

    /**
     * @var int
     */
    protected $lockTtlMilliseconds;

    /**
     * @var int
     */
    protected $retryDelayMicroseconds;

    /**
     * @var string|null
     */
    protected $token;

    /**
     * @var string|null
     */
    protected $sessionId;

    /**
     * @var bool
     */
    protected $isLocked = false;

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     * @param \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface $keyBuilder
     * @param int|null $timeoutMilliseconds
     * @param int|null $retryDelayMicroseconds
     * @param int|null $lockTtlMilliseconds
     */
    public function __construct(
        SessionRedisWrapperInterface $redisClient,
        SessionKeyBuilderInterface $keyBuilder,
        ?int $timeoutMilliseconds = null,
        ?int $retryDelayMicroseconds = null,
        ?int $lockTtlMilliseconds = null
    ) {
        $this->redisClient = $redisClient;
        $this->keyBuilder = $keyBuilder;
        $this->timeoutMilliseconds = $this->getTimeoutMilliseconds($timeoutMilliseconds);
        $this->retryDelayMicroseconds = $retryDelayMicroseconds ?: static::DEFAULT_RETRY_DELAY_MICROSECONDS;
        $this->lockTtlMilliseconds = $this->getLockTtlMilliseconds($lockTtlMilliseconds);
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function lock($sessionId): bool
    {
        $this->token = $this->generateToken();
        $this->sessionId = $sessionId;

        return $this->waitForLock();
    }

    /**
     * @return void
     */
    public function unlockCurrent(): void
    {
        if (!$this->isLocked) {
            return;
        }

        $this->unlock($this->sessionId, $this->token);

        $this->sessionId = null;
        $this->token = null;
        $this->isLocked = false;
    }

    /**
     * @param string $sessionId
     * @param string $token
     *
     * @return bool
     */
    public function unlock($sessionId, $token): bool
    {
        $lockKey = $this->generateLockKey($sessionId);

        return $this
            ->redisClient
            ->eval(
                $this->getUnlockScript(),
                1,
                $lockKey,
                $token
            );
    }

    /**
     * @param int|null $timeoutMilliseconds
     *
     * @return int
     */
    protected function getTimeoutMilliseconds(?int $timeoutMilliseconds): int
    {
        if ($timeoutMilliseconds) {
            return $timeoutMilliseconds;
        }

        return $this->getMillisecondsFromMaxExecutionTime(static::DEFAULT_TIMEOUT_MILLISECONDS, 0.8);
    }

    /**
     * @param int|null $lockTtlMilliseconds
     *
     * @return int
     */
    protected function getLockTtlMilliseconds(?int $lockTtlMilliseconds): int
    {
        if ($lockTtlMilliseconds) {
            return $lockTtlMilliseconds;
        }

        return $this->getMillisecondsFromMaxExecutionTime(static::DEFAULT_LOCK_TTL_MILLISECONDS);
    }

    /**
     * @param int $defaultMilliseconds
     * @param float $fraction
     *
     * @return int
     */
    protected function getMillisecondsFromMaxExecutionTime(int $defaultMilliseconds, float $fraction = 1.0): int
    {
        $maxExecutionTime = (int)round((int)ini_get('max_execution_time') * $fraction);

        if ($maxExecutionTime) {
            return ($maxExecutionTime * 1000);
        }

        return $defaultMilliseconds;
    }

    /**
     * @return string
     */
    protected function generateToken(): string
    {
        return random_bytes(20);
    }

    /**
     * @return bool
     */
    protected function waitForLock(): bool
    {
        $startTimeMilliseconds = $this->getMilliTime();

        do {
            if ($this->acquire()) {
                $this->isLocked = true;

                return true;
            }

            usleep($this->retryDelayMicroseconds);

            $runTimeMilliseconds = ($this->getMilliTime() - $startTimeMilliseconds);
        } while ($runTimeMilliseconds < $this->timeoutMilliseconds);

        return false;
    }

    /**
     * @return int
     */
    protected function getMilliTime(): int
    {
        return (int)round(microtime(true) * 1000);
    }

    /**
     * @return bool
     */
    protected function acquire(): bool
    {
        $lockKey = $this->generateLockKey($this->sessionId);

        $result = $this
            ->redisClient
            ->set($lockKey, $this->token, 'PX', $this->lockTtlMilliseconds, 'NX');

        return (bool)$result;
    }

    /**
     * @return string
     */
    protected function getUnlockScript(): string
    {
        return <<<LUA
if redis.call("GET", KEYS[1]) == ARGV[1] then
    return redis.call("DEL", KEYS[1])
end
return 0
LUA;
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    protected function generateLockKey(string $sessionId): string
    {
        return $this->keyBuilder->buildLockKey($sessionId);
    }
}
