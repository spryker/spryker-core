<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler;

use Spryker\Shared\SessionRedis\Handler\Exception\LockCouldNotBeAcquiredException;
use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface;
use Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

class SessionHandlerRedisWriteOnlyLocking extends SessionHandlerRedisLocking
{
    /**
     * @var array<\Spryker\Shared\SessionRedis\SessionConflictResolver\SessionConflictResolverInterface>
     */
    protected array $sessionConflictResolvers;

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     * @param \Spryker\Shared\SessionRedis\Handler\Lock\SessionLockerInterface $locker
     * @param \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface $keyBuilder
     * @param \Spryker\Shared\SessionRedis\Handler\LifeTime\SessionRedisLifeTimeCalculatorInterface $sessionRedisLifeTimeCalculator
     * @param array<\Spryker\Shared\SessionRedis\SessionConflictResolver\SessionConflictResolverInterface> $sessionConflictResolvers
     */
    public function __construct(
        SessionRedisWrapperInterface $redisClient,
        SessionLockerInterface $locker,
        SessionKeyBuilderInterface $keyBuilder,
        SessionRedisLifeTimeCalculatorInterface $sessionRedisLifeTimeCalculator,
        array $sessionConflictResolvers
    ) {
        parent::__construct($redisClient, $locker, $keyBuilder, $sessionRedisLifeTimeCalculator);

        $this->sessionConflictResolvers = $sessionConflictResolvers;
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function read($sessionId): string
    {
        $sessionData = $this->redisClient->get($this->keyBuilder->buildSessionKey($sessionId));

        return $this->normalizeSessionData($sessionData);
    }

    /**
     * @param string $sessionId
     * @param string $data
     *
     * @throws \Spryker\Shared\SessionRedis\Handler\Exception\LockCouldNotBeAcquiredException
     *
     * @return bool
     */
    public function write($sessionId, $data): bool
    {
        if (!$this->locker->lock($sessionId)) {
            throw new LockCouldNotBeAcquiredException(
                sprintf(
                    '%s could not acquire access to the session %s',
                    static::class,
                    $sessionId,
                ),
            );
        }

        $savedSession = $this->decodeSessionData($this->read($sessionId));
        $changedSessionData = $this->resolveSessionConflicts($savedSession, $_SESSION);
        if ($changedSessionData !== null) {
            $_SESSION = $changedSessionData;
            $data = session_encode() ?: $data;
        }

        $result = $this->redisClient->setex(
            $this->keyBuilder->buildSessionKey($sessionId),
            $this->sessionRedisLifeTimeCalculator->getSessionLifeTime(),
            $data,
        );

        $this->locker->unlockCurrent();

        return $result;
    }

    /**
     * @param array<string> $savedSession
     * @param array<string> $data
     *
     * @return array<string>|null
     */
    protected function resolveSessionConflicts(array $savedSession, array $data): ?array
    {
        foreach ($this->sessionConflictResolvers as $sessionConflictResolver) {
            if ($sessionConflictResolver->isApplicable($savedSession, $data)) {
                return $sessionConflictResolver->resolveSessionConflicts($savedSession, $data);
            }
        }

        return null;
    }

    /**
     * @param string $data
     *
     * @return array<string>
     */
    protected function decodeSessionData(string $data): array
    {
        $backupSession = $_SESSION;
        session_decode($data);

        $decodedData = $_SESSION;

        // restore backup
        $_SESSION = $backupSession;

        return $decodedData ?: [];
    }
}
