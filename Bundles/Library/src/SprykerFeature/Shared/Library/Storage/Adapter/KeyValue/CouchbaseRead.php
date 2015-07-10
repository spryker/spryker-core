<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

class CouchbaseRead extends Couchbase implements ReadInterface
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
        if (!($stats = $this->getResource()->getStats())) {
            $stats = [];
        }

        return $stats;
    }

    /**
     * @return array
     */
    public function getAllKeys()
    {
        try {
            $result = $this->getResource()->view('all', 'keys');
        } catch (\CouchbaseServerException $exception) {
            $this->createViews();
            try {
                $result = $this->getResource()->view('all', 'keys');
            } catch (\CouchbaseServerException $exception) {
                return [];
            }
        }

        return array_map(function ($item) { return $item['key']; }, $result['rows']);
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

    private function createViews()
    {
        $map = '{"views":{"keys":{"map":"function (doc, meta) { emit(meta.id, null); }"}}}';
        $this->getResource()->setDesignDoc('all', $map);
    }

}
