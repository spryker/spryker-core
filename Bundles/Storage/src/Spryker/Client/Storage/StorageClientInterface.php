<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Storage;

interface StorageClientInterface
{

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     */
    public function set($key, $value, $ttl = null);

    /**
     * @param array $items
     */
    public function setMulti(array $items);

    /**
     * @param string $key
     */
    public function delete($key);

    /**
     * @param array $keys
     */
    public function deleteMulti(array $keys);

    /**
     * @return int
     */
    public function deleteAll();

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys);

    /**
     * @return array
     */
    public function getStats();

    /**
     * @return array
     */
    public function getAllKeys();

    /**
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern);

    /**
     */
    public function resetAccessStats();

    /**
     * @return array
     */
    public function getAccessStats();

    /**
     * @return int
     */
    public function getCountItems();

}
