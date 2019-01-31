<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\StorageDatabase\StorageDatabaseFactory getFactory()
 */
class StorageDatabaseClient extends AbstractClient implements StorageDatabaseClientInterface
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
    public function set($key, $value, $ttl = null)
    {
        // TODO: Implement set() method.
    }

    /**
     * @api
     *
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items)
    {
        // TODO: Implement setMulti() method.
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        // TODO: Implement deleteMulti() method.
    }

    /**
     * @api
     *
     * @return int
     */
    public function deleteAll()
    {
        // TODO: Implement deleteAll() method.
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        // TODO: Implement get() method.
    }

    /**
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys)
    {
        // TODO: Implement getMulti() method.
    }

    /**
     * @api
     *
     * @return array
     */
    public function getStats()
    {
        // TODO: Implement getStats() method.
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAllKeys()
    {
        // TODO: Implement getAllKeys() method.
    }

    /**
     * @api
     *
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern)
    {
        // TODO: Implement getKeys() method.
    }

    /**
     * @api
     *
     * @return void
     */
    public function resetAccessStats()
    {
        // TODO: Implement resetAccessStats() method.
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAccessStats()
    {
        // TODO: Implement getAccessStats() method.
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCountItems()
    {
        // TODO: Implement getCountItems() method.
    }

    /**
     * @api
     *
     * @param bool $debug
     *
     * @return $this
     */
    public function setDebug($debug)
    {
        // TODO: Implement setDebug() method.
    }
}
