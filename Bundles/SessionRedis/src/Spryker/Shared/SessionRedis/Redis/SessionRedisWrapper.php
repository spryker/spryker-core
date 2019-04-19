<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Redis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface;

class SessionRedisWrapper implements SessionRedisWrapperInterface
{
    /**
     * @var \Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface
     */
    protected $redisClient;

    /**
     * @var string
     */
    protected $connectionKey;

    /**
     * @param \Spryker\Shared\SessionRedis\Dependency\Client\SessionRedisToRedisClientInterface $redisClient
     * @param string $connectionKey
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     */
    public function __construct(
        SessionRedisToRedisClientInterface $redisClient,
        string $connectionKey,
        RedisConfigurationTransfer $configurationTransfer
    ) {
        $this->redisClient = $redisClient;
        $this->connectionKey = $connectionKey;

        $this->setupConnection($configurationTransfer);
    }

    /**
     * @param mixed $key
     *
     * @return string
     */
    public function get($key)
    {
        return $this->redisClient->get($this->connectionKey, $key);
    }

    /**
     * @param mixed $key
     * @param mixed $seconds
     * @param mixed $value
     *
     * @return int
     */
    public function setex($key, $seconds, $value)
    {
        return $this->redisClient->setex($this->connectionKey, $key, $seconds, $value);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param mixed|null $expireResolution
     * @param mixed|null $expireTTL
     * @param mixed|null $flag
     *
     * @return mixed
     */
    public function set($key, $value, $expireResolution = null, $expireTTL = null, $flag = null)
    {
        return $this->redisClient->set($this->connectionKey, $key, $value, $expireResolution, $expireTTL, $flag);
    }

    /**
     * @param array $keys
     *
     * @return int
     */
    public function del($keys)
    {
        return $this->redisClient->del($this->connectionKey, $keys);
    }

    /**
     * @param mixed $script
     * @param mixed $numkeys
     * @param mixed|null $keyOrArg1
     * @param mixed|null $keyOrArgN
     *
     * @return mixed
     */
    public function eval($script, $numkeys, $keyOrArg1 = null, $keyOrArgN = null)
    {
        return $this->redisClient->eval($this->connectionKey, $script, $numkeys, $keyOrArg1, $keyOrArgN);
    }

    /**
     * @return void
     */
    public function connect()
    {
        $this->redisClient->connect($this->connectionKey);
    }

    /**
     * @return void
     */
    public function disconnect()
    {
        $this->redisClient->disconnect($this->connectionKey);
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->redisClient->isConnected($this->connectionKey);
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget($keys)
    {
        return $this->redisClient->mget($this->connectionKey, $keys);
    }

    /**
     * @param array $dictionary
     *
     * @return mixed
     */
    public function mset($dictionary)
    {
        return $this->redisClient->mset($this->connectionKey, $dictionary);
    }

    /**
     * @param mixed|null $section
     *
     * @return array
     */
    public function info($section = null)
    {
        return $this->redisClient->info($this->connectionKey, $section);
    }

    /**
     * @param mixed $pattern
     *
     * @return array
     */
    public function keys($pattern)
    {
        return $this->redisClient->keys($this->connectionKey, $pattern);
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    protected function setupConnection(RedisConfigurationTransfer $configurationTransfer): void
    {
        $this->redisClient->setupConnection(
            $this->connectionKey,
            $configurationTransfer
        );
    }
}
