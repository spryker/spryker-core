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
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $connectionKey, string $key): ?string;

    /**
     * @param string $connectionKey
     * @param string $key
     * @param int $seconds
     * @param string $value
     *
     * @return bool
     */
    public function setex(string $connectionKey, string $key, int $seconds, string $value): bool;

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
    public function set(string $connectionKey, string $key, string $value, ?string $expireResolution = null, ?int $expireTTL = null, ?string $flag = null): bool;

    /**
     * @param string $connectionKey
     * @param array $keys
     *
     * @return int
     */
    public function del(string $connectionKey, array $keys): int;

    /**
     * @param string $connectionKey
     * @param string $script
     * @param int $numKeys
     * @param array $keysOrArgs
     *
     * @return bool
     */
    public function eval(string $connectionKey, string $script, int $numKeys, ...$keysOrArgs): bool;

    /**
     * @param string $connectionKey
     *
     * @return void
     */
    public function connect(string $connectionKey): void;

    /**
     * @param string $connectionKey
     *
     * @return void
     */
    public function disconnect(string $connectionKey): void;

    /**
     * @param string $connectionKey
     *
     * @return bool
     */
    public function isConnected(string $connectionKey): bool;

    /**
     * @param string $connectionKey
     * @param array $keys
     *
     * @return array
     */
    public function mget(string $connectionKey, array $keys): array;

    /**
     * @param string $connectionKey
     * @param array $dictionary
     *
     * @return bool
     */
    public function mset(string $connectionKey, array $dictionary): bool;

    /**
     * @param string $connectionKey
     * @param string|null $section
     *
     * @return array
     */
    public function info(string $connectionKey, ?string $section = null): array;

    /**
     * @param string $connectionKey
     * @param string $pattern
     *
     * @return array
     */
    public function keys(string $connectionKey, string $pattern): array;

    /**
     * @param string $connectionKey
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    public function setupConnection(string $connectionKey, RedisConfigurationTransfer $configurationTransfer): void;
}
