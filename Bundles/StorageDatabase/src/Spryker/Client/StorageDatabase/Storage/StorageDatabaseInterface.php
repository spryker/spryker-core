<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage;

interface StorageDatabaseInterface
{
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
     * @return void
     */
    public function resetAccessStats(): void;

    /**
     * @return array
     */
    public function getAccessStats(): array;

    /**
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void;

    /**
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return void
     */
    public function set(string $key, string $value, ?int $ttl = null): void;

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
     * @return array
     */
    public function getStats(): array;

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
     * @return int
     */
    public function getCountItems(): int;
}
