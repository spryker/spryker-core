<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

class MemcachedReadWrite extends MemcachedRead implements ReadWriteInterface
{

    /**
     * @param $key
     * @param $value
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function set($key, $value)
    {
        $result = $this->getResource()->set($key, $value);
        $this->addWriteAccessStats($key);
        if (!$result) {
            throw new \Exception(
                'could not set memcacheKey: "' . $key . '" with value: "' . json_encode($value) . '"'
            );
        }

        return $result;
    }

    /**
     * @param array $items
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function setMulti(array $items)
    {
        $result = $this->getResource()->setMulti($items);
        $this->addMultiWriteAccessStats($items);
        if (!$result) {
            throw new \Exception(
                'could not set memcacheKeys for items: "[' . implode(',', array_keys($items)) . ']" with values: "[' . implode(',', array_values($items)) . ']"'
            );
        }

        return $result;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function delete($key)
    {
        $result = $this->getResource()->delete($key);
        $this->addDeleteAccessStats($key);

        return $result;
    }

    /**
     * @param array $keys
     */
    public function deleteMulti(array $keys)
    {
        $this->getResource()->deleteMulti($keys);
        $this->addMultiDeleteAccessStats($keys);
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        $deleteCount = 0;
        while ($keys = $this->getAllKeys()) {
            foreach ($keys as $key) {
                if ($this->getResource()->delete($key)) {
                    $deleteCount++;
                }
            }
        }

        return $deleteCount;
    }

}
