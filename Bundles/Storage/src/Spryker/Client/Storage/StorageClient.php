<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Storage\StorageFactory getFactory()
 */
class StorageClient extends AbstractClient implements StorageClientInterface
{

    /**
     * @api
     *
     * @return \Spryker\Client\Storage\StorageClientInterface $service
     */
    public function getService()
    {
        return $this->getFactory()->createCachedService();
    }

    /**
     * @api
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->getService()->set($key, $value);
    }

    /**
     * @api
     *
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items)
    {
        $this->getService()->setMulti($items);
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return void
     */
    public function delete($key)
    {
        $this->getService()->delete($key);
    }

    /**
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        $this->getService()->deleteMulti($keys);
    }

    /**
     * @api
     *
     * @return int
     */
    public function deleteAll()
    {
        return $this->getService()->deleteAll();
    }

    /**
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->getService()->get($key);
    }

    /**
     * @api
     *
     * @param array $keys
     *
     * @return array|null
     */
    public function getMulti(array $keys)
    {
        return $this->getService()->getMulti($keys);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getStats()
    {
        return $this->getService()->getStats();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAllKeys()
    {
        return $this->getService()->getAllKeys();
    }

    /**
     * @api
     *
     * @return void
     */
    public function resetAccessStats()
    {
        $this->getService()->resetAccessStats();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAccessStats()
    {
        return $this->getService()->getAccessStats();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCountItems()
    {
        return $this->getService()->getCountItems();
    }

    /**
     * @api
     *
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern = '*')
    {
        return $this->getService()->getKeys($pattern);
    }

}
