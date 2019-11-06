<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\StorageDatabase\Storage\StorageDatabaseInterface;

/**
 * @method \Spryker\Client\StorageDatabase\StorageDatabaseFactory getFactory()
 */
class StorageDatabaseClient extends AbstractClient implements StorageDatabaseClientInterface
{
    /**
     * @var \Spryker\Client\StorageDatabase\Storage\StorageDatabaseInterface
     */
    protected static $storageDatabaseService;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->getStorageDatabaseService()->get($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array
    {
        return $this->getStorageDatabaseService()->getMulti($keys);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function resetAccessStats(): void
    {
        $this->getStorageDatabaseService()->resetAccessStats();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getAccessStats(): array
    {
        return $this->getStorageDatabaseService()->getAccessStats();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void
    {
        $this->getStorageDatabaseService()->setDebug($debug);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return void
     */
    public function set(string $key, string $value, ?int $ttl = null): void
    {
        $this->getStorageDatabaseService()->set($key, $value, $ttl);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items): void
    {
        $this->getStorageDatabaseService()->setMulti($items);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return int
     */
    public function delete(string $key): int
    {
        return $this->getStorageDatabaseService()->delete($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $keys
     *
     * @return int
     */
    public function deleteMulti(array $keys): int
    {
        return $this->getStorageDatabaseService()->deleteMulti($keys);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int
     */
    public function deleteAll(): int
    {
        return $this->getStorageDatabaseService()->deleteAll();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getStats(): array
    {
        return $this->getStorageDatabaseService()->getStats();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getAllKeys(): array
    {
        return $this->getStorageDatabaseService()->getAllKeys();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys(string $pattern): array
    {
        return $this->getStorageDatabaseService()->getKeys($pattern);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int
     */
    public function getCountItems(): int
    {
        return $this->getStorageDatabaseService()->getCountItems();
    }

    /**
     * @return \Spryker\Client\StorageDatabase\Storage\StorageDatabaseInterface
     */
    protected function getStorageDatabaseService(): StorageDatabaseInterface
    {
        if (static::$storageDatabaseService === null) {
            static::$storageDatabaseService = $this->getFactory()->createStorageDatabaseService();
        }

        return static::$storageDatabaseService;
    }
}
