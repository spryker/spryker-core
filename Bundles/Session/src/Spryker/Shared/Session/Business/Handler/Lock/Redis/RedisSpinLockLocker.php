<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler\Lock\Redis;

use Predis\Client;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\LockKeyGeneratorInterface;
use Spryker\Shared\Session\Business\Handler\Lock\SessionLockerInterface;

class RedisSpinLockLocker implements SessionLockerInterface
{
    public const KEY_SUFFIX = ':lock';

    public const DEFAULT_TIMEOUT_MILLISECONDS = 10000;
    public const DEFAULT_RETRY_DELAY_MICROSECONDS = 10000;
    public const DEFAULT_LOCK_TTL_MILLISECONDS = 20000;

    public const LOG_METRIC_LOCK_ACQUIRE_TIME = 'Redis/Session_lock_acquire_time';
    public const LOG_METRIC_LOCK_RELEASE_TIME = 'Redis/Session_lock_release_time';
    public const LOG_METRIC_LOCK_WAIT_TIME = 'Redis/Session_lock_wait_time';

    /**
     * @var \Predis\Client
     */
    protected $redisClient;

    /**
     * @var \Spryker\Shared\Session\Business\Handler\KeyGenerator\LockKeyGeneratorInterface
     */
    protected $lockKeyGenerator;

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
     * @param \Predis\Client $redisClient
     * @param \Spryker\Shared\Session\Business\Handler\KeyGenerator\LockKeyGeneratorInterface $lockKeyGenerator
     * @param int|null $timeoutMilliseconds
     * @param int|null $retryDelayMicroseconds
     * @param int|null $lockTtlMilliseconds
     */
    public function __construct(
        Client $redisClient,
        LockKeyGeneratorInterface $lockKeyGenerator,
        $timeoutMilliseconds = null,
        $retryDelayMicroseconds = null,
        $lockTtlMilliseconds = null
    ) {
        $this->redisClient = $redisClient;
        $this->lockKeyGenerator = $lockKeyGenerator;
        $this->timeoutMilliseconds = $this->getTimeoutMilliseconds($timeoutMilliseconds);
        $this->retryDelayMicroseconds = $retryDelayMicroseconds ?: static::DEFAULT_RETRY_DELAY_MICROSECONDS;
        $this->lockTtlMilliseconds = $this->getLockTtlMilliseconds($lockTtlMilliseconds);
    }

    /**
     * @param int|null $timeoutMilliseconds
     *
     * @return int
     */
    protected function getTimeoutMilliseconds($timeoutMilliseconds)
    {
        if ((int)$timeoutMilliseconds) {
            return (int)$timeoutMilliseconds;
        }

        return $this->getMillisecondsFromMaxExecutionTime(static::DEFAULT_TIMEOUT_MILLISECONDS, 0.8);
    }

    /**
     * @param int|null $lockTtlMilliseconds
     *
     * @return int
     */
    protected function getLockTtlMilliseconds($lockTtlMilliseconds)
    {
        if ((int)$lockTtlMilliseconds) {
            return (int)$lockTtlMilliseconds;
        }

        return $this->getMillisecondsFromMaxExecutionTime(static::DEFAULT_LOCK_TTL_MILLISECONDS);
    }

    /**
     * @param int $defaultMilliseconds
     * @param float $fraction
     *
     * @return int
     */
    protected function getMillisecondsFromMaxExecutionTime($defaultMilliseconds, $fraction = 1.0)
    {
        $maxExecutionTime = (int)round((int)ini_get('max_execution_time') * $fraction);
        if ($maxExecutionTime) {
            return ($maxExecutionTime * 1000);
        }

        return $defaultMilliseconds;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function lock($sessionId)
    {
        $this->token = $this->generateToken();
        $this->sessionId = $sessionId;

        return $this->waitForLock();
    }

    /**
     * @return string
     */
    protected function generateToken()
    {
        return random_bytes(20);
    }

    /**
     * @return bool
     */
    protected function waitForLock()
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
    protected function getMilliTime()
    {
        return (int)round(microtime(true) * 1000);
    }

    /**
     * @return bool
     */
    protected function acquire()
    {
        $lockKey = $this->lockKeyGenerator->generateLockKey($this->sessionId);

        $result = $this
            ->redisClient
            ->set($lockKey, $this->token, 'PX', $this->lockTtlMilliseconds, 'NX');

        return ($result ? true : false);
    }

    /**
     * @return void
     */
    public function unlockCurrent()
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
    public function unlock($sessionId, $token)
    {
        $lockKey = $this->lockKeyGenerator->generateLockKey($sessionId);

        $result = $this
            ->redisClient
            ->eval(
                $this->getUnlockScript(),
                1,
                $lockKey,
                $token
            );

        return ($result ? true : false);
    }

    /**
     * @return string
     */
    protected function getUnlockScript()
    {
        return <<<LUA
if redis.call("GET", KEYS[1]) == ARGV[1] then
    return redis.call("DEL", KEYS[1])
end
return 0
LUA;
    }
}
