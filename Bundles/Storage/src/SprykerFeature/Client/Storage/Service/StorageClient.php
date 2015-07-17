<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Storage\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method StorageDependencyContainer getDependencyContainer()
 */
class StorageClient extends AbstractClient implements StorageClientInterface
{

    /**
     * @return StorageClientInterface $service
     */
    public function getService()
    {
        return $this->getDependencyContainer()->createService();
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->getService()->set($key, $value);
    }

    /**
     * @param array $items
     */
    public function setMulti(array $items)
    {
        $this->getService()->setMulti($items);
    }

    /**
     * @param string $key
     */
    public function delete($key)
    {
        $this->getService()->delete($key);
    }

    /**
     * @param array $keys
     */
    public function deleteMulti(array $keys)
    {
        $this->getService()->deleteMulti($keys);
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        return $this->getService()->deleteAll();
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->getService()->get($key);
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys)
    {
        return $this->getService()->getMulti($keys);
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return $this->getService()->getStats();
    }

    /**
     * @return array
     */
    public function getAllKeys()
    {
        return $this->getService()->getAllKeys();
    }

    /**
     */
    public function resetAccessStats()
    {
        $this->getService()->resetAccessStats();
    }

    /**
     * @return array
     */
    public function getAccessStats()
    {
        return $this->getService()->getAccessStats();
    }

    /**
     * @return int
     */
    public function getCountItems()
    {
        return $this->getService()->getCountItems();
    }

}
