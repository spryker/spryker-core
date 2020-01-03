<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Redis;

/**
 * @deprecated Use `Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface` instead.
 */
interface ServiceInterface
{
    /**
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
     * @api
     *
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items);

    /**
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function delete($key);

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
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys);

    /**
     * @api
     *
     * @return array
     */
    public function getStats();

    /**
     * @api
     *
     * @return array
     */
    public function getAllKeys();

    /**
     * @api
     *
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern);

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
     * @return int
     */
    public function getCountItems();

    /**
     * @param bool $debug
     *
     * @return $this
     */
    public function setDebug($debug);
}
