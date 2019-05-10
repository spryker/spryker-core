<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageExtension\Dependency\Plugin;

interface StoragePluginInterface
{
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
    public function set(string $key, string $value, $ttl = null): void;

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
     *
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys): void;

    /**
     * Specification:
     * - Deletes all values.
     *
     * @api
     *
     * @return int
     */
    public function deleteAll(): int;

    /**
     * Specification:
     * - Gets value of a key.
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
     * - Gets multiple values for multiple keys.
     *
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys);

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
     * - Resets access statistics.
     *
     * @api
     *
     * @return void
     */
    public function resetAccessStats(): void;

    /**
     * Specification:
     * - Gets access statistics.
     *
     * @api
     *
     * @return array
     */
    public function getAccessStats(): array;

    /**
     * Specification:
     * - Gets amount of items in the storage.
     *
     * @api
     *
     * @return int
     */
    public function getCountItems(): int;

    /**
     * Specification:
     * - Enables or disables debug mode.
     *
     * @api
     *
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void;
}
