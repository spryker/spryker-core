<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Redis;

use Exception;
use Predis\ClientInterface;

class Service implements ServiceInterface
{
    public const KV_PREFIX = 'kv:';

    /**
     * @var \Predis\ClientInterface
     */
    protected $client;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var array
     */
    protected $accessStats;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param \Predis\ClientInterface $client
     * @param bool $debug
     */
    public function __construct(ClientInterface $client, $debug = false)
    {
        $this->client = $client;
        $this->debug = $debug;
        $this->resetAccessStats();
    }

    /**
     * close redis connection
     */
    public function __destruct()
    {
        if ($this->client) {
            $this->client->disconnect();
        }
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param bool $debug
     *
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * Sets, reads, writes stats array
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

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $key = $this->getKeyName($key);
        $value = $this->client->get($key);
        $this->addReadAccessStats($key);

        $result = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            return $value;
        }

        return $result;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys)
    {
        if (count($keys) === 0) {
            return $keys;
        }

        $transformedKeys = [];
        foreach ($keys as $key) {
            $transformedKeys[] = $this->getKeyName($key);
        }

        $values = array_combine($transformedKeys, $this->client->mget($transformedKeys));
        $this->addMultiReadAccessStats($keys);

        return $values;
    }

    /**
     * @param string|null $section
     *
     * @return array
     */
    public function getStats($section = null)
    {
        return $this->client->info($section);
    }

    /**
     * @return array
     */
    public function getAllKeys()
    {
        return $this->getKeys('*');
    }

    /**
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern)
    {
        return $this->client->keys($this->getSearchPattern($pattern));
    }

    /**
     * @return int
     */
    public function getCountItems()
    {
        return count($this->client->keys($this->getSearchPattern()));
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    protected function getSearchPattern($pattern = '*')
    {
        return self::KV_PREFIX . $pattern;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getKeyName($key)
    {
        return self::KV_PREFIX . $key;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function set($key, $value, $ttl = null)
    {
        $key = $this->getKeyName($key);

        if ($ttl === null) {
            $result = $this->client->set($key, $value);
        } else {
            $result = $this->client->setex($key, $ttl, $value);
        }

        $this->addWriteAccessStats($key);
        if (!$result) {
            throw new Exception(
                'could not set redisKey: "' . $key . '" with value: "' . json_encode($value) . '"'
            );
        }

        return $result;
    }

    /**
     * @param array $items
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setMulti(array $items)
    {
        $data = [];

        foreach ($items as $key => $value) {
            $dataKey = $this->getKeyName($key);

            if (!is_scalar($value)) {
                $value = json_encode($value);
            }

            $data[$dataKey] = $value;
        }

        if (count($data) === 0) {
            return;
        }

        $result = $this->client->mset($data);
        $this->addMultiWriteAccessStats($data);

        if (!$result) {
            throw new Exception(
                'could not set redisKeys for items: "[' . implode(',', array_keys($items))
                . ']" with values: "[' . implode(',', array_values($items)) . ']"'
            );
        }
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function delete($key)
    {
        $key = $this->getKeyName($key);
        $result = $this->client->del([$key]);
        $this->addDeleteAccessStats($key);

        return $result;
    }

    /**
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        if (count($keys) === 0) {
            return;
        }

        $transformedKeys = [];
        foreach ($keys as $key) {
            $transformedKeys[] = $this->getKeyName($key);
        }

        $this->client->del($transformedKeys);
        $this->addMultiDeleteAccessStats($transformedKeys);
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        $keys = $this->getAllKeys();

        return $this->client->del($keys);
    }
}
