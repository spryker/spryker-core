<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Business\Handler;

use Predis\Client;
use SessionHandlerInterface;
use Spryker\Shared\Session\Business\Handler\Exception\LockCouldNotBeAcquiredException;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\SessionKeyGeneratorInterface;
use Spryker\Shared\Session\Business\Handler\Lock\SessionLockerInterface;

/**
 * @deprecated Use `Spryker\Shared\SessionRedis\Handler\SessionHandlerRedisLocking` instead.
 */
class SessionHandlerRedisLocking implements SessionHandlerInterface
{
    public const KEY_PREFIX = 'session:';

    /**
     * @var \Predis\Client
     */
    protected $redisClient;

    /**
     * @var int
     */
    protected $ttlSeconds;

    /**
     * @var \Spryker\Shared\Session\Business\Handler\Lock\SessionLockerInterface
     */
    protected $locker;

    /**
     * @var \Spryker\Shared\Session\Business\Handler\KeyGenerator\SessionKeyGeneratorInterface
     */
    protected $keyGenerator;

    /**
     * @param \Predis\Client $redisClient
     * @param \Spryker\Shared\Session\Business\Handler\Lock\SessionLockerInterface $locker
     * @param \Spryker\Shared\Session\Business\Handler\KeyGenerator\SessionKeyGeneratorInterface $keyGenerator
     * @param int $ttlSeconds
     */
    public function __construct(
        Client $redisClient,
        SessionLockerInterface $locker,
        SessionKeyGeneratorInterface $keyGenerator,
        $ttlSeconds
    ) {
        $this->redisClient = $redisClient;
        $this->locker = $locker;
        $this->keyGenerator = $keyGenerator;
        $this->ttlSeconds = $ttlSeconds;

        $this->redisClient->connect();
    }

    public function __destruct()
    {
        $this->locker->unlockCurrent();
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
        $this->locker->unlockCurrent();

        return true;
    }

    /**
     * @param string $sessionId
     *
     * @throws \Spryker\Shared\Session\Business\Handler\Exception\LockCouldNotBeAcquiredException
     *
     * @return string
     */
    public function read($sessionId)
    {
        if (!$this->locker->lock($this->keyGenerator->generateSessionKey($sessionId))) {
            throw new LockCouldNotBeAcquiredException(sprintf(
                '%s could not acquire access to the session %s',
                static::class,
                $sessionId
            ));
        }

        $sessionData = $this
            ->redisClient
            ->get($this->keyGenerator->generateSessionKey($sessionId));

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
            ->setex($this->keyGenerator->generateSessionKey($sessionId), $this->ttlSeconds, $data);

        return ($result ? true : false);
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId)
    {
        $this->redisClient->del([$this->keyGenerator->generateSessionKey($sessionId)]);
        $this->locker->unlockCurrent();

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
}
