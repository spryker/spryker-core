<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

interface StorageDatabaseClientInterface
{
    /**
     * Specification:
     *  - Gets the value by key from storage database.
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * Specification:
     *  - Gets multiple values by array of keys from storage database.
     *
     * @api
     *
     * @param string[] $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array;

    /**
     * Specification:
     *  - Resets in-memory access statistics for storage database.
     *
     * @api
     *
     * @return void
     */
    public function resetAccessStats(): void;

    /**
     * Specification:
     *  - Gets in-memory access statistics for storage database.
     *
     * @api
     *
     * @return array
     */
    public function getAccessStats(): array;

    /**
     * Specification:
     *  - Sets debug mode.
     *
     * @api
     *
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void;

    /**
     * Specification:
     * - Sets key to hold the value with an optional time-to-live parameter.
     *
     * @api
     *
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return void
     */
    public function set(string $key, string $value, ?int $ttl = null): void;

    /**
     * Specification:
     * - Sets multiple values.
     * - Accepts a key-value array as an argument.
     *
     * @api
     *
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items): void;

    /**
     * Specification:
     * - Deletes a value under specified key.
     *
     * @api
     *
     * @param string $key
     *
     * @return int
     */
    public function delete(string $key): int;

    /**
     * Specification:
     * - Deletes multiple values at a time.
     * - Accepts an array of keys for values to be deleted.
     * - Returns the number of entries that were deleted.
     *
     * @api
     *
     * @param array $keys
     *
     * @return int
     */
    public function deleteMulti(array $keys): int;

    /**
     * Specification:
     * - Deletes all values.
     * - Returns the number of entries that were deleted.
     *
     * @api
     *
     * @return int
     */
    public function deleteAll(): int;

    /**
     * Specification:
     * - Gets statistics about the server.
     *
     * @api
     *
     * @return array
     */
    public function getStats(): array;

    /**
     * Specification:
     * - Gets a list of all the keys available in a storage.
     *
     * @api
     *
     * @return array
     */
    public function getAllKeys(): array;

    /**
     * Specification:
     * - Gets all keys filtered by pattern.
     *
     * @api
     *
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys(string $pattern): array;

    /**
     * Specification:
     * - Gets amount of items in the storage.
     *
     * @api
     *
     * @return int
     */
    public function getCountItems(): int;
}
