<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Dependency\Client;

use Generated\Shared\Transfer\RedisConfigurationTransfer;

class SessionRedisToRedisClientBridge implements SessionRedisToRedisClientInterface
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
     * @param string $key
     * @param string $value
     * @param string|null $expireResolution
     * @param int|null $expireTTL
     * @param string|null $flag
     *
     * @return bool
     */
    public function set(string $connectionKey, string $key, string $value, ?string $expireResolution = null, ?int $expireTTL = null, ?string $flag = null): bool
    {
        return $this->redisClient->set($connectionKey, $key, $value, $expireResolution, $expireTTL, $flag);
    }

    /**
     * @param string $connectionKey
     * @param array $keys
     *
     * @return int
     */
    public function del(string $connectionKey, array $keys): int
    {
        return $this->redisClient->del($connectionKey, $keys);
    }

    /**
     * @param string $connectionKey
     * @param string $script
     * @param int $numKeys
     * @param array $keysOrArgs
     *
     * @return bool
     */
    public function eval(string $connectionKey, string $script, int $numKeys, ...$keysOrArgs): bool
    {
        return $this->redisClient->eval($connectionKey, $script, $numKeys, ...$keysOrArgs);
    }

    /**
     * @param string $connectionKey
     *
     * @return void
     */
    public function connect(string $connectionKey): void
    {
        $this->redisClient->connect($connectionKey);
    }

    /**
     * @param string $connectionKey
     *
     * @return void
     */
    public function disconnect(string $connectionKey): void
    {
        $this->redisClient->disconnect($connectionKey);
    }

    /**
     * @param string $connectionKey
     *
     * @return bool
     */
    public function isConnected(string $connectionKey): bool
    {
        return $this->redisClient->isConnected($connectionKey);
    }

    /**
     * @param string $connectionKey
     * @param array $keys
     *
     * @return array
     */
    public function mget(string $connectionKey, array $keys): array
    {
        return $this->redisClient->mget($connectionKey, $keys);
    }

    /**
     * @param string $connectionKey
     * @param array $dictionary
     *
     * @return bool
     */
    public function mset(string $connectionKey, array $dictionary): bool
    {
        return $this->redisClient->mset($connectionKey, $dictionary);
    }

    /**
     * @param string $connectionKey
     * @param string|null $section
     *
     * @return array
     */
    public function info(string $connectionKey, ?string $section = null): array
    {
        return $this->redisClient->info($connectionKey, $section);
    }

    /**
     * @param string $connectionKey
     * @param string $pattern
     *
     * @return array
     */
    public function keys(string $connectionKey, string $pattern): array
    {
        return $this->redisClient->keys($connectionKey, $pattern);
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
