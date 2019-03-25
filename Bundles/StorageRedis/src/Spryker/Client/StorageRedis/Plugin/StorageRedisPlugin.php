<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\StorageExtension\Dependency\StoragePluginInterface;

/**
 * @method \Spryker\Client\StorageRedis\StorageRedisFactory getFactory()
 * @method \Spryker\Client\StorageRedis\StorageRedisConfig getConfig()
 * @method \Spryker\Client\StorageRedis\StorageRedisClient getClient()
 */
class StorageRedisPlugin extends AbstractPlugin implements StoragePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @return void
     */
    public function set($key, $value, $ttl = null)
    {
        $this->getClient()->set($key, $value, $ttl);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items)
    {
        $this->getClient()->setMulti($items);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return void
     */
    public function delete($key)
    {
        $this->getClient()->delete($key);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        $this->getClient()->deleteMulti($keys);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int
     */
    public function deleteAll()
    {
        return $this->getClient()->deleteAll();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->getClient()->get($key);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array
    {
        return $this->getClient()->getMulti($keys);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getStats(): array
    {
        return $this->getClient()->getStats();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getAllKeys(): array
    {
        return $this->getClient()->getAllKeys();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern): array
    {
        return $this->getClient()->getKeys($pattern);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function resetAccessStats()
    {
        $this->getClient()->resetAccessStats();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getAccessStats(): array
    {
        return $this->getClient()->getAccessStats();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int
     */
    public function getCountItems()
    {
        return $this->getClient()->getCountItems();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug($debug)
    {
        $this->getClient()->setDebug($debug);
    }
}
