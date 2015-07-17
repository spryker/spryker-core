<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\KeyValue;

class CouchbaseReadWrite extends CouchbaseRead implements ReadWriteInterface
{

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->getResource()->set($key, $value);
        $this->addWriteAccessStats($key);
    }

    /**
     * @param array $items
     */
    public function setMulti(array $items)
    {
        if (count($items) === 0) {
            return;
        }
        $this->getResource()->setMulti($items);
        $this->addMultiWriteAccessStats($items);
    }

    /**
     * @param string $key
     */
    public function delete($key)
    {
        $this->getResource()->delete($key);
        $this->addDeleteAccessStats($key);
    }

    /**
     * @param array $keys
     */
    public function deleteMulti(array $keys)
    {
        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            $this->getResource()->delete($key);
        }

        $this->addMultiDeleteAccessStats($keys);
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        $count = $this->getCountItems();
        $this->getResource()->flush();

        return $count;
    }

}
