<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler;

use Predis\Client;
use SessionHandlerInterface;
use Spryker\Shared\Session\Business\Handler\Locker\SessionLockerInterface;
use Spryker\Shared\Session\Business\Handler\Logger\SessionTimedLoggerInterface;

class SessionHandlerRedisLocking implements SessionHandlerInterface
{

    const KEY_PREFIX = 'session:';

    const LOG_METRIC_READ_TIME = 'Redis/Session_read_time';
    const LOG_METRIC_WRITE_TIME = 'Redis/Session_write_time';
    const LOG_METRIC_DELETE_TIME = 'Redis/Session_delete_time';

    /**
     * @var \Predis\Client
     */
    protected $redisClient;

    /**
     * @var int
     */
    protected $ttlSeconds;

    /**
     * @var \Spryker\Shared\Session\Business\Handler\Locker\SessionLockerInterface
     */
    protected $locker;

    /**
     * @var \Spryker\Shared\Session\Business\Handler\Logger\SessionTimedLoggerInterface $logger
     */
    protected $logger;

    /**
     * @param \Predis\Client $redisClient
     * @param \Spryker\Shared\Session\Business\Handler\Locker\SessionLockerInterface $locker
     * @param int $ttlSeconds
     * @param \Spryker\Shared\Session\Business\Handler\Logger\SessionTimedLoggerInterface $logger
     */
    public function __construct(
        Client $redisClient,
        SessionLockerInterface $locker,
        $ttlSeconds,
        SessionTimedLoggerInterface $logger
    ) {
        $this->redisClient = $redisClient;
        $this->locker = $locker;
        $this->ttlSeconds = $ttlSeconds;
        $this->logger = $logger;
    }

    /**
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        $this->redisClient->connect();

        return $this->redisClient->isConnected();
    }

    /**
     * @return bool
     */
    public function close()
    {
        $this->locker->unlock();
        $this->redisClient->disconnect();

        return true;
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function read($sessionId)
    {
        if (!$this->locker->lock($this->getKey($sessionId))) {
            return '';
        }

        $this->logger->startTiming();

        $sessionData = $this
            ->redisClient
            ->get($this->getKey($sessionId));

        $this->logger->logTimedMetric(static::LOG_METRIC_READ_TIME);

        return $this->normalizeSessionData($sessionData);
    }

    /**
     * @param string $sessionData
     *
     * @return string
     */
    protected function normalizeSessionData($sessionData)
    {
        if (!$sessionData) {
            return '';
        }

        return $this->tryDecodeLegacySession($sessionData);
    }

    /**
     * @param string $sessionData
     *
     * @return string
     */
    protected function tryDecodeLegacySession($sessionData)
    {
        if (substr($sessionData, 0, 1) === '"') {
            return json_decode($sessionData);
        }

        return $sessionData;
    }

    /**
     * @param string $sessionId
     * @param string $data
     *
     * @return bool
     */
    public function write($sessionId, $data)
    {
        $this->logger->startTiming();

        $result = $this
            ->redisClient
            ->setex($this->getKey($sessionId), $this->ttlSeconds, $data);

        $this->logger->logTimedMetric(static::LOG_METRIC_WRITE_TIME);

        return ($result ? true : false);
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $this->logger->startTiming();

        $this->redisClient->del([$this->getKey($sessionId)]);

        $this->logger->logTimedMetric(static::LOG_METRIC_DELETE_TIME);

        return true;
    }

    /**
     * @param int|string $maxLifeTime
     *
     * @return bool
     */
    public function gc($maxLifeTime)
    {
        return true;
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    protected function getKey($sessionId)
    {
        return static::KEY_PREFIX . $sessionId;
    }

}
