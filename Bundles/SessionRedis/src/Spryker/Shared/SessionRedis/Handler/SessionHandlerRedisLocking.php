<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler;

use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Handler\Exception\LockCouldNotBeAcquiredException;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
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
     * @var \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     * @param \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface $locker
     * @param \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface $keyBuilder
     * @param int $ttlSeconds
     */
    public function __construct(
        SessionRedisWrapperInterface $redisClient,
        SessionLockerInterface $locker,
        SessionKeyBuilderInterface $keyBuilder,
        $ttlSeconds
    ) {
        $this->redisClient = $redisClient;
        $this->locker = $locker;
        $this->ttlSeconds = $ttlSeconds;
        $this->keyBuilder = $keyBuilder;
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
        if (!$this->locker->lock($sessionId)) {
            throw new LockCouldNotBeAcquiredException(
                sprintf(
                    '%s could not acquire access to the session %s',
                    SessionHandlerRedisLocking::class,
                    $sessionId
                )
            );
        }

        $sessionData = $this->redisClient->get($this->keyBuilder->buildSessionKey($sessionId));

        return $this->normalizeSessionData($sessionData);
    }

    /**
     * @param string $sessionId
     * @param string $data
     *
     * @return bool
     */
    public function write($sessionId, $data): bool
    {
        return $this->redisClient->setex($this->keyBuilder->buildSessionKey($sessionId), $this->ttlSeconds, $data);
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId): bool
    {
        $this->redisClient->del([$this->keyBuilder->buildSessionKey($sessionId)]);
        $this->locker->unlockCurrent();

        return true;
    }

    /**
     * @param int $maxLifeTime
     *
     * @return bool
     */
    public function gc($maxLifeTime): bool
    {
        return true;
    }

    /**
     * @param string|null $sessionData
     *
     * @return string
     */
    protected function normalizeSessionData(?string $sessionData = null): string
    {
        if (!$sessionData) {
            return '';
        }

        $sessionData = $this->tryDecodeLegacySession($sessionData);

        if ($sessionData === null) {
            return '';
        }

        return $sessionData;
    }

    /**
     * @param string $sessionData
     *
     * @return string|null
     */
    protected function tryDecodeLegacySession(string $sessionData): ?string
    {
        if (substr($sessionData, 0, 1) === '"') {
            return json_decode($sessionData, true);
        }

        return $sessionData;
    }
}
