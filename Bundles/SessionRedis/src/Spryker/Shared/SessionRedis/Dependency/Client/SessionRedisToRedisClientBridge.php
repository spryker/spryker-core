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
     * @param mixed $key
     *
     * @return string
     */
    public function get(string $connectionKey, $key)
    {
        return $this->redisClient->get($connectionKey, $key);
    }

    /**
     * @param string $connectionKey
     * @param mixed $key
     * @param mixed $seconds
     * @param mixed $value
     *
     * @return int
     */
    public function setex(string $connectionKey, $key, $seconds, $value)
    {
        return $this->redisClient->setex($connectionKey, $key, $seconds, $value);
    }

    /**
     * @param string $connectionKey
     * @param mixed $key
     * @param mixed $value
     * @param mixed|null $expireResolution
     * @param mixed|null $expireTTL
     * @param mixed|null $flag
     *
     * @return mixed
     */
    public function set(string $connectionKey, $key, $value, $expireResolution = null, $expireTTL = null, $flag = null)
    {
        return $this->redisClient->set($connectionKey, $key, $value, $expireResolution, $expireTTL, $flag);
    }

    /**
     * @param string $connectionKey
     * @param array $keys
     *
     * @return int
     */
    public function del(string $connectionKey, $keys)
    {
        return $this->redisClient->del($connectionKey, $keys);
    }

    /**
     * @param string $connectionKey
     * @param mixed $script
     * @param mixed $numkeys
     * @param mixed|null $keyOrArg1
     * @param mixed|null $keyOrArgN
     *
     * @return mixed
     */
    public function eval(string $connectionKey, $script, $numkeys, $keyOrArg1 = null, $keyOrArgN = null)
    {
        return $this->redisClient->eval($connectionKey, $script, $numkeys, $keyOrArg1, $keyOrArgN);
    }

    /**
     * @param string $connectionKey
     *
     * @return void
     */
    public function connect(string $connectionKey)
    {
        $this->redisClient->connect($connectionKey);
    }

    /**
     * @param string $connectionKey
     *
     * @return void
     */
    public function disconnect(string $connectionKey)
    {
        $this->redisClient->disconnect($connectionKey);
    }

    /**
     * @param string $connectionKey
     *
     * @return bool
     */
    public function isConnected(string $connectionKey)
    {
        return $this->redisClient->isConnected($connectionKey);
    }

    /**
     * @param string $connectionKey
     * @param array $keys
     *
     * @return array
     */
    public function mget(string $connectionKey, $keys)
    {
        return $this->redisClient->mget($connectionKey, $keys);
    }

    /**
     * @param string $connectionKey
     * @param array $dictionary
     *
     * @return mixed
     */
    public function mset(string $connectionKey, $dictionary)
    {
        return $this->redisClient->mset($connectionKey, $dictionary);
    }

    /**
     * @param string $connectionKey
     * @param mixed|null $section
     *
     * @return array
     */
    public function info(string $connectionKey, $section = null)
    {
        return $this->redisClient->info($connectionKey, $section);
    }

    /**
     * @param string $connectionKey
     * @param mixed $pattern
     *
     * @return array
     */
    public function keys(string $connectionKey, $pattern)
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
