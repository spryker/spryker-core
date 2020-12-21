<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Dependency\Client;

use Generated\Shared\Transfer\RedisConfigurationTransfer;

class SecurityBlockerToRedisClientBridge implements SecurityBlockerToRedisClientInterface
{
    /**
     * @var \Spryker\Client\Redis\RedisClientInterface
     */
    protected $redisClient;

    /**
     * @param \Spryker\Client\Redis\RedisClientInterface $redisClient
     */
    public function __construct($redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @param string $connectionKey
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $connectionKey, string $key): ?string
    {
        return $this->redisClient->get($connectionKey, $key);
    }

    /**
     * @param string $connectionKey
     * @param string $key
     *
     * @return int
     */
    public function incr(string $connectionKey, string $key): int
    {
        return $this->redisClient->incr($connectionKey, $key);
    }

    /**
     * @param string $connectionKey
     * @param string $key
     * @param int $seconds
     * @param string $value
     *
     * @return bool
     */
    public function setex(string $connectionKey, string $key, int $seconds, string $value): bool
    {
        return $this->redisClient->setex($connectionKey, $key, $seconds, $value);
    }

    /**
     * @param string $connectionKey
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    public function setupConnection(string $connectionKey, RedisConfigurationTransfer $configurationTransfer): void
    {
        $this->redisClient->setupConnection($connectionKey, $configurationTransfer);
    }
}
