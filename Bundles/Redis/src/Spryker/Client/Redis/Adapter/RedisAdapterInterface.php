<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Redis\Adapter;

interface RedisAdapterInterface
{
    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string;

    /**
     * @param string $key
     * @param int $seconds
     * @param string $value
     *
     * @return bool
     */
    public function setex(string $key, int $seconds, string $value): bool;

    /**
     * @param string $key
     * @param string $value
     * @param string|null $expireResolution
     * @param int|null $expireTTL
     * @param string|null $flag
     *
     * @return bool
     */
    public function set(string $key, string $value, ?string $expireResolution = null, ?int $expireTTL = null, ?string $flag = null): bool;

    /**
     * @param array $keys
     *
     * @return int
     */
    public function del(array $keys): int;

    /**
     * @param string $script
     * @param int $numKeys
     * @param array $keysOrArgs
     *
     * @return bool
     */
    public function eval(string $script, int $numKeys, $keysOrArgs): bool;

    /**
     * @return void
     */
    public function connect(): void;

    /**
     * @return void
     */
    public function disconnect(): void;

    /**
     * @return bool
     */
    public function isConnected(): bool;

    /**
     * @param array $keys
     *
     * @return array
     */
    public function mget(array $keys): array;

    /**
     * @param array $dictionary
     *
     * @return bool
     */
    public function mset(array $dictionary): bool;

    /**
     * @param string|null $section
     *
     * @return array
     */
    public function info(?string $section = null): array;

    /**
     * @param string $pattern
     *
     * @return string[]
     */
    public function keys(string $pattern): array;
}
