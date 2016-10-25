<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Storage\Client;

use Spryker\Shared\Library\Storage\AdapterInterface;
use Spryker\Shared\Library\Storage\AdapterTrait;

/**
 * @deprecated Not used anymore.
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
     *
     * @return void
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
     * @param string $key
     *
     * @return void
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
     *
     * @return void
     */
    protected function addMultiReadAccessStats(array $keys)
    {
        if ($this->debug) {
            $this->accessStats['count']['read'] += count($keys);
            $this->accessStats['keys']['read'] = $this->accessStats['keys']['read'] + $keys;
        }
    }

    /**
     * @param string $key
     *
     * @return void
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
     *
     * @return void
     */
    protected function addMultiWriteAccessStats(array $items)
    {
        if ($this->debug) {
            $this->accessStats['count']['write'] += count($items);
            $this->accessStats['keys']['write'] = $this->accessStats['keys']['write'] + array_keys($items);
        }
    }

    /**
     * @param string $key
     *
     * @return void
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
     *
     * @return void
     */
    protected function addMultiDeleteAccessStats(array $keys)
    {
        if ($this->debug) {
            $this->accessStats['count']['delete'] += count($keys);
            $this->accessStats['keys']['delete'] = $this->accessStats['keys']['delete'] + $keys;
        }
    }

}
