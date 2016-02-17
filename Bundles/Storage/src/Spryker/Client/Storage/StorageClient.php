<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Storage;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Log\LoggerTrait;

/**
 * @method \Spryker\Client\Storage\StorageFactory getFactory()
 */
class StorageClient extends AbstractClient implements StorageClientInterface
{

    use LoggerTrait;

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface $service
     */
    public function getService()
    {
        return $this->getFactory()->createCachedService();
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     *
     * @return void
     */
    public function set($key, $value, $ttl = null)
    {
        $this->getService()->set($key, $value, $ttl);
    }

    /**
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items)
    {
        $this->getService()->setMulti($items);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function delete($key)
    {
        $this->getService()->delete($key);
    }

    /**
     * @param array $keys
     *
     * @return void
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
//        $this->getLogger()->info('GET: ' .$key);
        return $this->getService()->get($key);
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys)
    {
//        $this->getLogger()->info('MULTI: ' . implode(', ', $keys));
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
     * @return void
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

    /**
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern = '*')
    {
        return $this->getService()->getKeys($pattern);
    }

}
