<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

/**
 * Interface ReadWriteInterface
 */
interface ReadWriteInterface extends ReadInterface
{

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value);

    /**
     * @param array $items
     * @param string $prefix
     * 
     * @return bool|mixed
     */
    public function setMulti(array $items, $prefix = RedisRead::KV_PREFIX);

    /**
     * @param $key
     * @param string $prefix
     * 
     * @return bool|mixed
     */
    public function delete($key, $prefix = RedisRead::KV_PREFIX);

    /**
     * @param array $keys
     * @param string $prefix
     * 
     * @return bool|mixed
     */
    public function deleteMulti(array $keys, $prefix = RedisRead::KV_PREFIX);

    /**
     * @return mixed
     */
    public function deleteAll();

}
