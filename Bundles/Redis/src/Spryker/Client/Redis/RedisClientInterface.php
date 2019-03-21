<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;

interface RedisClientInterface
{
    /**
     * Specification:
     * - Gets the value of key.
     * - @see https://redis.io/commands/get
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     *
     * @return string
     */
    public function get(string $connectionKey, string $key);

    /**
     * Specification:
     * - Sets key to hold the string value and sets key to timeout after a given number of seconds.
     * - @see https://redis.io/commands/setex
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     * @param int $seconds
     * @param mixed $value
     *
     * @return int
     */
    public function setex(string $connectionKey, string $key, int $seconds, $value);

    /**
     * Specification:
     * - Sets key to hold the string value.
     * - @see https://redis.io/commands/set
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     * @param mixed $value
     * @param mixed|null $expireResolution
     * @param int|null $expireTTL
     * @param mixed|null $flag
     *
     * @return mixed
     */
    public function set(string $connectionKey, string $key, $value, $expireResolution = null, ?int $expireTTL = null, $flag = null);

    /**
     * Specification:
     * - Removes the specified keys.
     * - @see https://redis.io/commands/del
     *
     * @api
     *
     * @param string $connectionKey
     * @param array $keys
     *
     * @return int
     */
    public function del(string $connectionKey, array $keys);

    /**
     * Specification:
     * - Used to evaluate scripts using the Lua interpreter built into Redis.
     * - @see https://redis.io/commands/eval
     *
     * @api
     *
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
     * Specification:
     * - Opens the underlying connection and connects to the server.
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return void
     */
    public function connect(string $connectionKey);

    /**
     * Specification:
     * - Closes the underlying connection and disconnects from the server.
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return void
     */
    public function disconnect(string $connectionKey);

    /**
     * Specification:
     * - Returns the current state of the underlying connection.
     *
     * @api
     *
     * @param string $connectionKey
     *
     * @return bool
     */
    public function isConnected(string $connectionKey): bool;

    /**
     * Specification:
     * - Returns the values of all specified keys.
     * - @see https://redis.io/commands/mget
     *
     * @api
     *
     * @param string $connectionKey
     * @param array $keys
     *
     * @return array
     */
    public function mget(string $connectionKey, array $keys);

    /**
     * Specification:
     * - Sets the given keys to their respective values.
     * - @see https://redis.io/commands/mset
     *
     * @api
     *
     * @param string $connectionKey
     * @param array $dictionary
     *
     * @return mixed
     */
    public function mset(string $connectionKey, array $dictionary);

    /**
     * Specification:
     * - Returns information and statistics about the server.
     * - @see https://redis.io/commands/info
     *
     * @api
     *
     * @param string $connectionKey
     * @param mixed|null $section
     *
     * @return array
     */
    public function info(string $connectionKey, $section = null);

    /**
     * Specification:
     * - Returns all keys matching pattern.
     * - @see https://redis.io/commands/keys
     *
     * @api
     *
     * @param string $connectionKey
     * @param mixed $pattern
     *
     * @return array
     */
    public function keys(string $connectionKey, $pattern);

    /**
     * Specification:
     * - Initializes a connection with provided configuration, if it was not already initialized.
     * - Sets the initialized connection as the one to be used for all subsequent requests.
     *
     * @api
     *
     * @param string $connectionKey
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    public function setupConnection(string $connectionKey, RedisConfigurationTransfer $configurationTransfer): void;
}
