<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\KvStorage\Service\Redis;

interface RedisServiceInterface
{

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config);

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @return boolean
     */
    public function getDebug();

    /**
     * @param $debug
     * @return $this
     */
    public function setDebug($debug);

    /**
     * @throws \MemcachedException
     */
    public function connect();

    /**
     * set read write stats array
     */
    public function resetAccessStats();

    /**
     * @return array
     */
    public function getAccessStats();

    /**
     * @param string $key
     * @param string $prefix
     * @return mixed|string
     */
    public function get($key, $prefix = self::KV_PREFIX);

    /**
     * @param array $keys
     * @param string $prefix
     * @return array
     */
    public function getMulti(array $keys, $prefix = self::KV_PREFIX);

    /**
     * @param null|string $section
     * @return array
     */
    public function getStats($section = null);

    /**
     * @param null|string $prefix
     * @return array
     */
    public function getAllKeys($prefix = self::KV_PREFIX);

    /**
     * @param null|string $prefix
     * @return int
     */
    public function getCountItems($prefix = self::KV_PREFIX);

    /**
     * @param string $key
     * @param mixed $value
     * @param string $prefix
     * @return mixed
     * @throws \Exception
     */
    public function set($key, $value, $prefix = self::KV_PREFIX);

    /**
     * @param array $items
     * @param string $prefix
     * @return bool|mixed
     * @throws \Exception
     */
    public function setMulti(array $items, $prefix = self::KV_PREFIX);

    /**
     * @param string $key
     * @param null|string $prefix
     * @return int
     */
    public function delete($key, $prefix = self::KV_PREFIX);

    /**
     * @param array $keys
     * @return void
     */
    public function deleteMulti(array $keys);

    /**
     * @return int
     */
    public function deleteAll();
}
