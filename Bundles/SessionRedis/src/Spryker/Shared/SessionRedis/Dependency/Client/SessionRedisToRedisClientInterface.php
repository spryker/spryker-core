<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Dependency\Client;

use Generated\Shared\Transfer\RedisConfigurationTransfer;

interface SessionRedisToRedisClientInterface
{
    /**
     * @param string $connectionKey
     * @param mixed $key
     *
     * @return string
     */
    public function get(string $connectionKey, $key);

    /**
     * @param string $connectionKey
     * @param mixed $key
     * @param mixed $seconds
     * @param mixed $value
     *
     * @return int
     */
    public function setex(string $connectionKey, $key, $seconds, $value);

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
    public function set(string $connectionKey, $key, $value, $expireResolution = null, $expireTTL = null, $flag = null);

    /**
     * @param string $connectionKey
     * @param array $keys
     *
     * @return int
     */
    public function del(string $connectionKey, $keys);

    /**
     * @param string $connectionKey
     * @param mixed $script
     * @param mixed $numkeys
     * @param mixed|null $keyOrArg1
     * @param mixed|null $keyOrArgN
     *
     * @return mixed
     */
    public function eval(string $connectionKey, $script, $numkeys, $keyOrArg1 = null, $keyOrArgN = null);

    /**
     * @param string $connectionKey
     *
     * @return void
     */
    public function connect(string $connectionKey);

    /**
     * @param string $connectionKey
     *
     * @return void
     */
    public function disconnect(string $connectionKey);

    /**
     * @param string $connectionKey
     *
     * @return bool
     */
    public function isConnected(string $connectionKey);

    /**
     * @param string $connectionKey
     * @param array $keys
     *
     * @return array
     */
    public function mget(string $connectionKey, $keys);

    /**
     * @param string $connectionKey
     * @param array $dictionary
     *
     * @return mixed
     */
    public function mset(string $connectionKey, $dictionary);

    /**
     * @param string $connectionKey
     * @param mixed|null $section
     *
     * @return array
     */
    public function info(string $connectionKey, $section = null);

    /**
     * @param string $connectionKey
     * @param mixed $pattern
     *
     * @return array
     */
    public function keys(string $connectionKey, $pattern);

    /**
     * @param string $connectionKey
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    public function setupConnection(string $connectionKey, RedisConfigurationTransfer $configurationTransfer): void;
}
