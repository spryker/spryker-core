<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

/**
 * Class RedisRead
 */
class RedisRead extends Redis implements ReadInterface
{

    const KV_PREFIX = 'kv:';

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return mixed|string
     */
    public function get($key, $prefix = self::KV_PREFIX)
    {
        $key = $this->getKeyName($key, $prefix);
        $value = $this->getResource()->get($key);
        $this->addReadAccessStats($key);

        $result = json_decode($value, true);

        if (json_last_error() === \JSON_ERROR_SYNTAX) {
            return $value;
        }

        return $result;
    }

    /**
     * @param array $keys
     * @param string $prefix
     *
     * @return array
     */
    public function getMulti(array $keys, $prefix = self::KV_PREFIX)
    {
        $transformedKeys = [];
        foreach ($keys as $key) {
            $transformedKeys[] = $this->getKeyName($key, $prefix);
        }

        $values = array_combine($transformedKeys, $this->getResource()->mget($transformedKeys));
        $this->addMultiReadAccessStats($keys);

        return $values;
    }

    /**
     * @param null|string $section
     *
     * @return array
     */
    public function getStats($section = null)
    {
        return $this->getResource()->info($section);
    }

    /**
     * @param null|string $prefix
     *
     * @return array
     */
    public function getAllKeys($prefix = self::KV_PREFIX)
    {
        return $this->getResource()->keys($this->getSearchPattern($prefix));
    }

    /**
     * @param null|string $prefix
     *
     * @return int
     */
    public function getCountItems($prefix = self::KV_PREFIX)
    {
        return count($this->getResource()->keys($this->getSearchPattern($prefix)));
    }

    /**
     * @param null|string $prefix
     *
     * @return string
     */
    protected function getSearchPattern($prefix = self::KV_PREFIX)
    {
        return $prefix ? $prefix . '*' : '*';
    }

    /**
     * @param string $key
     * @param string $prefix
     *
     * @return string
     */
    protected function getKeyName($key, $prefix = self::KV_PREFIX)
    {
        return $prefix ? $prefix . $key : $key;
    }

}
