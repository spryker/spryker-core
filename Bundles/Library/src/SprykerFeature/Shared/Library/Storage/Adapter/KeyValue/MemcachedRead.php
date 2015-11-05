<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

class MemcachedRead extends Memcached implements ReadInterface
{

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $value = $this->getResource()->get($key);
        $this->addReadAccessStats($key);

        return $value;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys)
    {
        $values = array_fill_keys($keys, null);
        $values = array_merge($values, $this->getResource()->getMulti($keys));
        $this->addMultiReadAccessStats($keys);

        return $values;
    }

    /**
     * @return array
     */
    public function getStats()
    {
        $stats = $this->getResource()->getStats();
        if (!$stats) {
            $stats = [];
        }

        return $stats;
    }

    /**
     * @return array
     */
    public function getAllKeys()
    {
        return $this->getResource()->getAllKeys();
    }

    /**
     * @return int
     */
    public function getCountItems()
    {
        $stats = $this->getStats();
        $currentStats = array_pop($stats);

        return (int) $currentStats['curr_items'];
    }

}
