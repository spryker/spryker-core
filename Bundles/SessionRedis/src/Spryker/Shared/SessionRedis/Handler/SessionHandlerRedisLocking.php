<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler;

use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Handler\Exception\LockCouldNotBeAcquiredException;
use Spryker\Shared\SessionRedis\Handler\KeyGenerator\SessionKeyGeneratorInterface;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

class SessionHandlerRedisLocking implements SessionHandlerInterface
{
    public const KEY_PREFIX = 'session:';

    /**
     * @var \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected $redisClient;

    /**
     * @var int
     */
    protected $ttlSeconds;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface
     */
    protected $locker;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\KeyGenerator\SessionKeyGeneratorInterface
     */
    protected $keyGenerator;

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     * @param \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface $locker
     * @param \Spryker\Shared\SessionRedis\Handler\KeyGenerator\SessionKeyGeneratorInterface $keyGenerator
     * @param int $ttlSeconds
     */
    public function __construct(
        SessionRedisWrapperInterface $redisClient,
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
    public function open($savePath, $sessionName): bool
    {
        return $this->redisClient->isConnected();
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        $this->locker->unlockCurrent();

        return true;
    }

    /**
     * @param string $sessionId
     *
     * @throws \Spryker\Shared\SessionRedis\Handler\Exception\LockCouldNotBeAcquiredException
     *
     * @return string
     */
    public function read($sessionId): string
    {
        if (!$this->locker->lock($this->keyGenerator->generateSessionKey($sessionId))) {
            throw new LockCouldNotBeAcquiredException(
                sprintf(
                    '%s could not acquire access to the session %s',
                    SessionHandlerRedisLocking::class,
                    $sessionId
                )
            );
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
    protected function normalizeSessionData($sessionData): string
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
