<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis\Redis;

interface StorageRedisWrapperInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function set(string $key, $value, ?int $ttl = null): bool;

    /**
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items): void;

    /**
     * @param string $key
     *
     * @return int
     */
    public function delete(string $key): int;

    /**
     * @param array $keys
     *
     * @return int
     */
    public function deleteMulti(array $keys): int;

    /**
     * @return int
     */
    public function deleteAll(): int;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array;

    /**
     * @param string|null $section
     *
     * @return array
     */
    public function getStats(?string $section = null): array;

    /**
     * @return array
     */
    public function getAllKeys(): array;

    /**
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys(string $pattern): array;

    /**
     * @return void
     */
    public function resetAccessStats(): void;

    /**
     * @return array
     */
    public function getAccessStats(): array;

    /**
     * @return int
     */
    public function getCountItems(): int;

    /**
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void;
}
