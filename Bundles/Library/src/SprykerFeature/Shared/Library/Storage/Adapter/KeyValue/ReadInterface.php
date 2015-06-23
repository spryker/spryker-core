<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

/**
 * Interface ReadInterface
 * @package SprykerFeature\Shared\Library\Storage\Adapter\KeyValue
 */
interface ReadInterface
{
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
     * @return void
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
