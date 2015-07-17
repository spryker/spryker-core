<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

use SprykerFeature\Shared\Library\Storage\AdapterInterface;
use SprykerFeature\Shared\Library\Storage\AdapterTrait;

/**
 * Class AbstractKeyValue
 */
abstract class AbstractKeyValue implements AdapterInterface
{

    use AdapterTrait;

    /**
     * @var array
     */
    protected $accessStats;

    /**
     * @param array $config
     * @param bool $debug
     */
    public function __construct(array $config, $debug = false)
    {
        $this->config = $config;
        $this->debug = $debug;
        $this->resetAccessStats();
    }

    /**
     * set read write stats array
     */
    public function resetAccessStats()
    {
        $this->accessStats = [
            'count' => [
                'read' => 0,
                'write' => 0,
                'delete' => 0,
            ],
            'keys' => [
                'read' => [],
                'write' => [],
                'delete' => [],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAccessStats()
    {
        return $this->accessStats;
    }

    /**
     * @param $key
     */
    protected function addReadAccessStats($key)
    {
        if ($this->debug) {
            $this->accessStats['count']['read']++;
            $this->accessStats['keys']['read'][] = $key;
        }
    }

    /**
     * @param array $keys
     */
    protected function addMultiReadAccessStats(array $keys)
    {
        if ($this->debug) {
            $this->accessStats['count']['read'] += count($keys);
            $this->accessStats['keys']['read'] = $this->accessStats['keys']['read'] + $keys;
        }
    }

    /**
     * @param $key
     */
    protected function addWriteAccessStats($key)
    {
        if ($this->debug) {
            $this->accessStats['count']['write']++;
            $this->accessStats['keys']['write'][] = $key;
        }
    }

    /**
     * @param array $items
     */
    protected function addMultiWriteAccessStats(array $items)
    {
        if ($this->debug) {
            $this->accessStats['count']['write'] += count($items);
            $this->accessStats['keys']['write'] = $this->accessStats['keys']['write'] + array_keys($items);
        }
    }

    /**
     * @param $key
     */
    protected function addDeleteAccessStats($key)
    {
        if ($this->debug) {
            $this->accessStats['count']['delete']++;
            $this->accessStats['keys']['delete'][] = $key;
        }
    }

    /**
     * @param array $keys
     */
    protected function addMultiDeleteAccessStats(array $keys)
    {
        if ($this->debug) {
            $this->accessStats['count']['delete'] += count($keys);
            $this->accessStats['keys']['delete'] = $this->accessStats['keys']['delete'] + $keys;
        }
    }

}
