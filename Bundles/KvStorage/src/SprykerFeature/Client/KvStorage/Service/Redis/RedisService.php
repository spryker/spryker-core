<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\KvStorage\Service\Redis;

use Predis\Client;
use Predis\Connection\ConnectionException;

class RedisService implements RedisServiceInterface
{

    const KV_PREFIX = 'kv:';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Client
     */
    protected $resource;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $resource
     * @return $this
     */
    protected function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getResource()
    {
        if (!$this->resource) {
            $this->connect();
        }

        return $this->resource;
    }

    /**
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param $debug
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @throws \MemcachedException
     */
    public function connect()
    {
        if (!$this->resource) {
            $resource = new Client(
                [
                    'protocol' => $this->config['protocol'],
                    'host' => $this->config['host'],
                    'port' => $this->config['port'],
                ]
            );

            if (!$resource) {
                throw new ConnectionException($resource, 'Could not connect to redis server');
            }

            $this->resource = $resource;
        }
    }

    /**
     * close redis connection
     */
    public function __destruct()
    {
        if ($this->resource) {
            $this->resource->disconnect();
        }
    }

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
     * @param string $key
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
     * @param string $key
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
     * @param string $key
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

    /**
     * @param string $key
     * @param string $prefix
     * @return mixed|string
     */
    public function get($key, $prefix = self::KV_PREFIX)
    {
        $key = $this->getKeyName($key, $prefix);
        $value = $this->getResource()->get($key);
        $this->addReadAccessStats($key);

        $result = json_decode($value, true);

        if (json_last_error() == \JSON_ERROR_SYNTAX) {
            return $value;
        }

        return $result;
    }

    /**
     * @param array $keys
     * @param string $prefix
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
     * @return array
     */
    public function getStats($section = null)
    {
        return $this->getResource()->info($section);
    }

    /**
     * @param null|string $prefix
     * @return array
     */
    public function getAllKeys($prefix = self::KV_PREFIX)
    {
        return $this->getResource()->keys($this->getSearchPattern($prefix));
    }

    /**
     * @param null|string $prefix
     * @return int
     */
    public function getCountItems($prefix = self::KV_PREFIX)
    {
        return count($this->getResource()->keys($this->getSearchPattern($prefix)));
    }

    /**
     * @param null|string $prefix
     * @return string
     */
    protected function getSearchPattern($prefix = self::KV_PREFIX)
    {
        return $prefix ? $prefix . '*' : '*';
    }

    /**
     * @param string $key
     * @param string $prefix
     * @return string
     */
    protected function getKeyName($key, $prefix = self::KV_PREFIX)
    {
        return $prefix ? $prefix . $key : $key;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string $prefix
     * @return mixed
     * @throws \Exception
     */
    public function set($key, $value, $prefix = self::KV_PREFIX)
    {
        $key = $this->getKeyName($key, $prefix);
        $result = $this->getResource()->set($key, $value);
        $this->addWriteAccessStats($key);
        if (!$result) {
            throw new \Exception(
                'could not set redisKey: "' . $key . '" with value: "' . json_encode($value) . '"'
            );
        }

        return $result;
    }

    /**
     * @param array $items
     * @param string $prefix
     * @return bool|mixed
     * @throws \Exception
     */
    public function setMulti(array $items, $prefix = self::KV_PREFIX)
    {
        $data = [];

        foreach ($items as $key => $value) {
            $dataKey = $this->getKeyName($key, $prefix);

            if (!is_scalar($value)) {
                $value = json_encode($value);
            }

            $data[$dataKey] = $value;
        }

        if (count($data) == 0) {
            return false;
        }

        $result = $this->getResource()->mset($data);
        $this->addMultiWriteAccessStats($data);

        if (!$result) {
            throw new \Exception(
                'could not set redisKeys for items: "[' . implode(',',
                    array_keys($items)) . ']" with values: "[' . implode(',', array_values($items)) . ']"'
            );
        }

        return $result;
    }

    /**
     * @param string $key
     * @param null|string $prefix
     * @return int
     */
    public function delete($key, $prefix = self::KV_PREFIX)
    {
        $key = $this->getKeyName($key, $prefix);
        $result = $this->getResource()->del([$key]);
        $this->addDeleteAccessStats($key);

        return $result;
    }

    /**
     * @param array $keys
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        $this->getResource()->del($keys);
        $this->addMultiDeleteAccessStats($keys);
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        $keys = $this->getAllKeys();
        $deleteCount = count($keys);
        $this->deleteMulti($keys);

        return $deleteCount;
    }

}
