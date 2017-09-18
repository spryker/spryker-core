<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

interface StorageClientInterface
{

    /**
     * @api
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     * @param string $prefix
     *
     * @return mixed
     */
    public function set($key, $value, $ttl = null, $prefix = '');

    /**
     * @api
     *
     * @param array $items
     * @param string $prefix
     *
     * @return void
     */
    public function setMulti(array $items, $prefix = '');

    /**
     * @api
     *
     * @param string $key
     * @param string $prefix
     *
     * @return mixed
     */
    public function delete($key, $prefix = '');

    /**
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys);

    /**
     * @api
     *
     * @return int
     */
    public function deleteAll();

    /**
     * @api
     *
     * @param string $key
     * @param string $prefix
     *
     * @return mixed
     */
    public function get($key, $prefix = '');

    /**
     * @api
     *
     * @param array $keys
     * @param string $prefix
     *
     * @return array
     */
    public function getMulti(array $keys, $prefix = '');

    /**
     * @api
     *
     * @return array
     */
    public function getStats();

    /**
     * @api
     *
     * @param string $prefix
     *
     * @return array
     */
    public function getAllKeys($prefix = '');

    /**
     * @api
     *
     * @param string $pattern
     * @param string $prefix
     *
     * @return array
     */
    public function getKeys($pattern, $prefix = '');

    /**
     * @api
     *
     * @return void
     */
    public function resetAccessStats();

    /**
     * @api
     *
     * @return array
     */
    public function getAccessStats();

    /**
     * @api
     *
     * @param string $prefix
     *
     * @return int
     */
    public function getCountItems($prefix = '');

    /**
     * @api
     *
     * @return \Spryker\Client\Storage\StorageClientInterface $service
     */
    public function getService();

    /**
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function setCachedKeys($keys);

    /**
     * @api
     *
     * @return array
     */
    public function getCachedKeys();

    /**
     * @api
     *
     * @param string $key
     *
     * @return void
     */
    public function unsetCachedKey($key);

    /**
     * @api
     *
     * @return void
     */
    public function unsetLastCachedKey();

}
