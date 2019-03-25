<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageExtension\Dependency;

interface StoragePluginInterface
{
    /**
     * Specification:
     * - Sets key to hold the value with an optional time-to-live parameter.
     *
     * @api
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function set($key, $value, $ttl = null);

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
    public function setMulti(array $items);

    /**
     * Specification:
     * - Deletes a value under specified key.
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function delete($key);

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
    public function deleteMulti(array $keys);

    /**
     * Specification:
     * - Deletes all values.
     *
     * @api
     *
     * @return int
     */
    public function deleteAll();

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
    public function get($key);

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
    public function getStats();

    /**
     * Specification:
     * - Gets a list of all the keys available in a storage.
     *
     * @api
     *
     * @return array
     */
    public function getAllKeys();

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
    public function getKeys($pattern);

    /**
     * Specification:
     * - Resets access statistics.
     *
     * @api
     *
     * @return void
     */
    public function resetAccessStats();

    /**
     * Specification:
     * - Gets access statistics.
     *
     * @api
     *
     * @return array
     */
    public function getAccessStats();

    /**
     * Specification:
     * - Gets amount of items in the storage.
     *
     * @api
     *
     * @return int
     */
    public function getCountItems();

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
    public function setDebug($debug);
}
