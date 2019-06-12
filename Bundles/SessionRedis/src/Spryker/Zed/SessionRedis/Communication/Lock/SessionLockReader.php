<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis\Communication\Lock;

use Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;

class SessionLockReader implements SessionLockReaderInterface
{
    /**
     * @var \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface
     */
    protected $redisClient;

    /**
     * @var \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $redisClient
     * @param \Spryker\Shared\SessionRedis\Handler\KeyBuilder\SessionKeyBuilderInterface $keyBuilder
     */
    public function __construct(SessionRedisWrapperInterface $redisClient, SessionKeyBuilderInterface $keyBuilder)
    {
        $this->redisClient = $redisClient;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param string $sessionId
     *
     * @return string|null
     */
    public function getTokenForSession($sessionId): ?string
    {
        $lockKey = $this->keyBuilder->buildLockKey($sessionId);
        $token = $this->redisClient->get($lockKey);

        return $token;
    }
}
