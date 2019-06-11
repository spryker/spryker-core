<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business\Lock\Redis;

use Predis\Client;
use Spryker\Shared\Session\Business\Handler\KeyGenerator\LockKeyGeneratorInterface;
use Spryker\Zed\Session\Business\Lock\SessionLockReaderInterface;

/**
 * @deprecated Use `Spryker\Zed\SessionExtension\Dependency\Plugin\SessionLockReleaserPluginInterface` instead.
 */
class RedisSessionLockReader implements SessionLockReaderInterface
{
    /**
     * @var \Predis\Client
     */
    protected $redisClient;

    /**
     * @var \Spryker\Shared\Session\Business\Handler\KeyGenerator\LockKeyGeneratorInterface
     */
    protected $keyGenerator;

    /**
     * @param \Predis\Client $redisClient
     * @param \Spryker\Shared\Session\Business\Handler\KeyGenerator\LockKeyGeneratorInterface $keyGenerator
     */
    public function __construct(Client $redisClient, LockKeyGeneratorInterface $keyGenerator)
    {
        $this->redisClient = $redisClient;
        $this->keyGenerator = $keyGenerator;
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function getTokenForSession($sessionId)
    {
        $lockKey = $this->keyGenerator->generateLockKey($sessionId);
        $token = $this->redisClient->get($lockKey);

        return $token;
    }
}
