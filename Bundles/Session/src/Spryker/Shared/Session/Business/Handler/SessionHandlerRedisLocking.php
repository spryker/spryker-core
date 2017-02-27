<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler;

use Predis\Client;
use SessionHandlerInterface;
use Spryker\Shared\Session\Business\Handler\Locker\SessionLockerInterface;

class SessionHandlerRedisLocking implements SessionHandlerInterface
{

    const KEY_PREFIX = 'session:';

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
     * @param \Predis\Client $redisClient
     * @param \Spryker\Shared\Session\Business\Handler\Locker\SessionLockerInterface $locker
     * @param int $ttlSeconds
     */
    public function __construct(
        Client $redisClient,
        SessionLockerInterface $locker,
        $ttlSeconds
    ) {
        $this->redisClient = $redisClient;
        $this->locker = $locker;
        $this->ttlSeconds = $ttlSeconds;

        $this->redisClient->connect();
    }

    public function __destruct()
    {
        $this->locker->unlock();
        $this->redisClient->disconnect();
    }

    /**
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        return $this->redisClient->isConnected();
    }

    /**
     * @return bool
     */
    public function close()
    {
        $this->locker->unlock();

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

        $sessionData = $this
            ->redisClient
            ->get($this->getKey($sessionId));

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
            return json_decode($sessionData, true);
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
        $result = $this
            ->redisClient
            ->setex($this->getKey($sessionId), $this->ttlSeconds, $data);

        return ($result ? true : false);
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $this->redisClient->del([$this->getKey($sessionId)]);
        $this->locker->unlock();

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
