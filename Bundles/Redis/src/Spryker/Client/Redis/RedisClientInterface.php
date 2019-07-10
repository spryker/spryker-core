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
     * @return string|null
     */
    public function get(string $connectionKey, string $key): ?string;

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
     * @param string $value
     *
     * @return bool
     */
    public function setex(string $connectionKey, string $key, int $seconds, string $value): bool;

    /**
     * Specification:
     * - Sets key to hold the string value.
     * - @see https://redis.io/commands/set
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $key
     * @param string $value
     * @param string|null $expireResolution
     * @param int|null $expireTTL
     * @param string|null $flag
     *
     * @return bool
     */
    public function set(string $connectionKey, string $key, string $value, ?string $expireResolution = null, ?int $expireTTL = null, ?string $flag = null): bool;

    /**
     * Specification:
     * - Removes the specified keys.
     * - @see https://redis.io/commands/del
     *
     * @api
     *
     * @param string $connectionKey
     * @param string[] $keys
     *
     * @return int
     */
    public function del(string $connectionKey, array $keys): int;

    /**
     * Specification:
     * - Used to evaluate scripts using the Lua interpreter built into Redis.
     * - @see https://redis.io/commands/eval
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $script
     * @param int $numKeys
     * @param array $keysOrArgs
     *
     * @return bool
     */
    public function eval(string $connectionKey, string $script, int $numKeys, ...$keysOrArgs): bool;

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
    public function connect(string $connectionKey): void;

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
    public function disconnect(string $connectionKey): void;

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
     * @param string[] $keys
     *
     * @return array
     */
    public function mget(string $connectionKey, array $keys): array;

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
     * @return bool
     */
    public function mset(string $connectionKey, array $dictionary): bool;

    /**
     * Specification:
     * - Returns information and statistics about the server.
     * - @see https://redis.io/commands/info
     *
     * @api
     *
     * @param string $connectionKey
     * @param string|null $section
     *
     * @return array
     */
    public function info(string $connectionKey, ?string $section = null): array;

    /**
     * Specification:
     * - Returns all keys matching pattern.
     * - @see https://redis.io/commands/keys
     *
     * @api
     *
     * @param string $connectionKey
     * @param string $pattern
     *
     * @return string[]
     */
    public function keys(string $connectionKey, string $pattern): array;

    /**
     * Specification:
     * - Initializes a connection with provided configuration, if it was not already initialized.
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
