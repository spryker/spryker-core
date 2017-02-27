<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler\Locker;

use Predis\Client;

class RedisSpinLockLocker implements SessionLockerInterface
{

    const KEY_SUFFIX = ':lock';

    const DEFAULT_TIMEOUT_MILLISECONDS = 10000;
    const DEFAULT_RETRY_DELAY_MICROSECONDS = 10000;
    const DEFAULT_LOCK_TTL_MILLISECONDS = 20000;

    const LOG_METRIC_LOCK_ACQUIRE_TIME = 'Redis/Session_lock_acquire_time';
    const LOG_METRIC_LOCK_RELEASE_TIME = 'Redis/Session_lock_release_time';
    const LOG_METRIC_LOCK_WAIT_TIME = 'Redis/Session_lock_wait_time';

    /**
     * @var \Predis\Client
     */
    protected $redisClient;

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
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var bool
     */
    protected $isLocked = false;

    /**
     * @param \Predis\Client $redisClient
     * @param int|null $timeoutMilliseconds
     * @param int|null $retryDelayMicroseconds
     * @param int|null $lockTtlMilliseconds
     */
    public function __construct(
        Client $redisClient,
        $timeoutMilliseconds = null,
        $retryDelayMicroseconds = null,
        $lockTtlMilliseconds = null
    ) {
        $this->redisClient = $redisClient;
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

        return $this->getMillisecondsFromMaxExecutionTime(static::DEFAULT_TIMEOUT_MILLISECONDS);
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

        return $this->getMillisecondsFromMaxExecutionTime(static::DEFAULT_LOCK_TTL_MILLISECONDS, 0.75);
    }

    /**
     * @param int $defaultMilliseconds
     * @param int $fraction
     *
     * @return int
     */
    protected function getMillisecondsFromMaxExecutionTime($defaultMilliseconds, $fraction = 1)
    {
        $maxExecutionTime = (int)round((int)ini_get('max_execution_time') * $fraction);
        if ($maxExecutionTime) {
            return ($maxExecutionTime * 1000);
        }

        return $defaultMilliseconds;
    }

    /**
     * @param string $sessionKey
     *
     * @return bool
     */
    public function lock($sessionKey)
    {
        $this->token = $this->generateToken();
        $this->key = $this->generateKey($sessionKey);

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
     * @param string $sessionKey
     *
     * @return string
     */
    protected function generateKey($sessionKey)
    {
        return $sessionKey . static::KEY_SUFFIX;
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
        $result = $this
            ->redisClient
            ->set($this->key, $this->token, 'PX', $this->lockTtlMilliseconds, 'NX');

        return ($result ? true : false);
    }

    /**
     * @return void
     */
    public function unlock()
    {
        if (!$this->isLocked) {
            return;
        }

        $this
            ->redisClient
            ->eval(
                $this->getUnlockScript(),
                1,
                $this->key,
                $this->token
            );

        $this->key = null;
        $this->token = null;
        $this->isLocked = false;
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
