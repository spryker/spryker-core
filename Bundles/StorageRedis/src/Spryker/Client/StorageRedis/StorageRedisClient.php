<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis;

use Generated\Shared\Transfer\StorageScanResultTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\StorageRedis\Redis\StorageRedisWrapperInterface;

/**
 * @method \Spryker\Client\StorageRedis\StorageRedisFactory getFactory()
 */
class StorageRedisClient extends AbstractClient implements StorageRedisClientInterface
{
    /**
     * @var \Spryker\Client\StorageRedis\Redis\StorageRedisWrapperInterface
     */
    protected static $storageRedisWrapper;
    
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return bool
     */
    public function set(string $key, string $value, ?int $ttl = null): bool
    {
        return $this->getStorageRedisWrapper()->set($key, $value, $ttl);
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
        $this->getStorageRedisWrapper()->setMulti($items);
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
        return $this->getStorageRedisWrapper()->delete($key);
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
        return $this->getStorageRedisWrapper()->deleteMulti($keys);
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
        return $this->getStorageRedisWrapper()->deleteAll();
    }

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
        return $this->getStorageRedisWrapper()->get($key);
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
        return $this->getStorageRedisWrapper()->getMulti($keys);
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
        return $this->getStorageRedisWrapper()->getStats();
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
        return $this->getStorageRedisWrapper()->getAllKeys();
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
        return $this->getStorageRedisWrapper()->getKeys($pattern);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $pattern
     * @param int $limit
     * @param int $cursor
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, int $cursor): StorageScanResultTransfer
    {
        return $this->getStorageRedisWrapper()->scanKeys($pattern, $limit, $cursor);
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
        $this->getStorageRedisWrapper()->resetAccessStats();
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
        return $this->getStorageRedisWrapper()->getAccessStats();
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
        return $this->getStorageRedisWrapper()->getCountItems();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int
     */
    public function getDbSize(): int
    {
        return $this->getStorageRedisWrapper()->getDbSize();
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
        $this->getStorageRedisWrapper()->setDebug($debug);
    }

    /**
     * @return \Spryker\Client\StorageRedis\Redis\StorageRedisWrapperInterface
     */
    protected function getStorageRedisWrapper(): StorageRedisWrapperInterface
    {
        if (static::$storageRedisWrapper === null) {
            static::$storageRedisWrapper = $this->getFactory()->createStorageRedisWrapper();
        }
        
        return static::$storageRedisWrapper;
    }
}
