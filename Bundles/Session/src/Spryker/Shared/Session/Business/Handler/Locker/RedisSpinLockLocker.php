<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler\Locker;

use Predis\Client;
use Spryker\Shared\Session\Business\Handler\Logger\SessionTimedLoggerInterface;

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
     * @var \Spryker\Shared\Session\Business\Handler\Logger\SessionTimedLoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $key;

    /**
     * @param \Predis\Client $redisClient
     * @param \Spryker\Shared\Session\Business\Handler\Logger\SessionTimedLoggerInterface $logger
     * @param int|null $timeoutMilliseconds
     * @param int|null $retryDelayMicroseconds
     * @param int|null $lockTtlMilliseconds
     */
    public function __construct(
        Client $redisClient,
        SessionTimedLoggerInterface $logger,
        $timeoutMilliseconds = null,
        $retryDelayMicroseconds = null,
        $lockTtlMilliseconds = null
    ) {
        $this->redisClient = $redisClient;
        $this->logger = $logger;
        $this->timeoutMilliseconds = $timeoutMilliseconds ?: static::DEFAULT_TIMEOUT_MILLISECONDS;
        $this->retryDelayMicroseconds = $retryDelayMicroseconds ?: static::DEFAULT_RETRY_DELAY_MICROSECONDS;
        $this->lockTtlMilliseconds = $lockTtlMilliseconds ?: static::DEFAULT_LOCK_TTL_MILLISECONDS;
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
        return uniqid();
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
        $this->logger->startTiming();

        $startTimeMilliseconds = $this->getMilliTime();

        do {
            if ($this->acquire()) {
                $this->logger->logTimedMetric(static::LOG_METRIC_LOCK_WAIT_TIME);

                return true;
            }

            usleep($this->retryDelayMicroseconds);

            $runTimeMilliseconds = ($this->getMilliTime() - $startTimeMilliseconds);
        } while ($runTimeMilliseconds < $this->timeoutMilliseconds);

        $this->logger->logTimedMetric(static::LOG_METRIC_LOCK_WAIT_TIME);

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
        $this->logger->startTiming();

        $result = $this
            ->redisClient
            ->set($this->key, $this->token, 'PX', $this->lockTtlMilliseconds, 'NX');

        $this->logger->logTimedMetric(static::LOG_METRIC_LOCK_ACQUIRE_TIME);

        return ($result ? true : false);
    }

    /**
     * @return void
     */
    public function unlock()
    {
        $this->logger->startTiming();

        $this
            ->redisClient
            ->eval(
                $this->getUnlockScript(),
                1,
                $this->key,
                $this->token
            );

        $this->logger->logTimedMetric(static::LOG_METRIC_LOCK_RELEASE_TIME);

        $this->key = null;
        $this->token = null;
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
