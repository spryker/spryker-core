<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Storage\Service;

interface StorageClientInterface
{

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value);

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
