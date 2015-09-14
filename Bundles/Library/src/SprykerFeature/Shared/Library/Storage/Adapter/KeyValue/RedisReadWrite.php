<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

class RedisReadWrite extends RedisRead implements ReadWriteInterface
{

    /**
     * @param string $key
     * @param mixed $value
     * @param string $prefix
     *
     * @throws \Exception
     *
     * @return mixed
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
     *
     * @throws \Exception
     *
     * @return bool|mixed
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

        if (count($data) === 0) {
            return false;
        }

        $result = $this->getResource()->mset($data);
        $this->addMultiWriteAccessStats($data);

        if (!$result) {
            throw new \Exception(
                'could not set redisKeys for items: "[' . implode(',', array_keys($items)) . ']" with values: "[' . implode(',', array_values($items)) . ']"'
            );
        }

        return $result;
    }

    /**
     * @param string $key
     * @param null|string $prefix
     *
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
     * @param string $prefix
     * 
     * @return void
     */
    public function deleteMulti(array $keys, $prefix = self::KV_PREFIX)
    {
        $items = [];
        foreach ($keys as $key => $value) {
            $dataKey = $this->getKeyName($key, $prefix);
            $items[] = $dataKey;
        }
        
        $this->getResource()->del($items);
        $this->addMultiDeleteAccessStats($items);
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
