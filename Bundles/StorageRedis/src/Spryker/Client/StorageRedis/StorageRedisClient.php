<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis;

use Generated\Shared\Transfer\StorageScanResultTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\StorageRedis\StorageRedisFactory getFactory()
 */
class StorageRedisClient extends AbstractClient implements StorageRedisClientInterface
{
    /**
     * {@inheritdoc}
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
        return $this->getFactory()->createStorageRedisWrapper()->set($key, $value, $ttl);
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
    public function setMulti(array $items): void
    {
        $this->getFactory()->createStorageRedisWrapper()->setMulti($items);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return int
     */
    public function delete(string $key): int
    {
        return $this->getFactory()->createStorageRedisWrapper()->delete($key);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $keys
     *
     * @return int
     */
    public function deleteMulti(array $keys): int
    {
        return $this->getFactory()->createStorageRedisWrapper()->deleteMulti($keys);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int
     */
    public function deleteAll(): int
    {
        return $this->getFactory()->createStorageRedisWrapper()->deleteAll();
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
    public function get(string $key)
    {
        return $this->getFactory()->createStorageRedisWrapper()->get($key);
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
        return $this->getFactory()->createStorageRedisWrapper()->getMulti($keys);
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
        return $this->getFactory()->createStorageRedisWrapper()->getStats();
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
        return $this->getFactory()->createStorageRedisWrapper()->getAllKeys();
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
    public function getKeys(string $pattern): array
    {
        return $this->getFactory()->createStorageRedisWrapper()->getKeys($pattern);
    }

    /**
     * {@inheritdoc}
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
        return $this->getFactory()->createStorageRedisWrapper()->scanKeys($pattern, $limit, $cursor);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function resetAccessStats(): void
    {
        $this->getFactory()->createStorageRedisWrapper()->resetAccessStats();
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
        return $this->getFactory()->createStorageRedisWrapper()->getAccessStats();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int
     */
    public function getCountItems(): int
    {
        return $this->getFactory()->createStorageRedisWrapper()->getCountItems();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int
     */
    public function getDbSize(): int
    {
        return $this->getFactory()->createStorageRedisWrapper()->getDbSize();
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
    public function setDebug(bool $debug): void
    {
        $this->getFactory()->createStorageRedisWrapper()->setDebug($debug);
    }
}
