<?php
namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

/**
 * Interface ReadWriteInterface
 *
 * @package SprykerFeature\Shared\Library\Storage\Adapter\KeyValue
 */
interface ReadWriteInterface extends ReadInterface
{
    /**
     * @param string $key
     * @param mixed  $value
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
}
