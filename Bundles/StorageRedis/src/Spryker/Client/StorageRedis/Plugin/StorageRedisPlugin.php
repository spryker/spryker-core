<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis\Plugin;

use Generated\Shared\Transfer\StorageScanResultTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface;
use Spryker\Client\StorageExtension\Dependency\Plugin\StorageScanPluginInterface;

/**
 * - The method `getKeys()` uses Redis `KEYS` command which should only be used in production environments with extreme care.
 * - The methods `scanKeys()` and `getCountItems()` uses Redis `SCAN` and `DBSIZE` commands.
 * - Be aware that `SCAN` offers limited guarantees about the returned elements because it's non-blocking command.
 *
 * @method \Spryker\Client\StorageRedis\StorageRedisFactory getFactory()
 * @method \Spryker\Client\StorageRedis\StorageRedisConfig getConfig()
 * @method \Spryker\Client\StorageRedis\StorageRedisClientInterface getClient()
 */
class StorageRedisPlugin extends AbstractPlugin implements StoragePluginInterface, StorageScanPluginInterface
{
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
        $this->getClient()->set($key, $value, $ttl);
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
        $this->getClient()->setMulti($items);
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
        return $this->getClient()->delete($key);
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
        return $this->getClient()->deleteMulti($keys);
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
        return $this->getClient()->deleteAll();
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
        return $this->getClient()->get($key);
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
        return $this->getClient()->getMulti($keys);
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
        return $this->getClient()->getStats();
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
        return $this->getClient()->getAllKeys();
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
        return $this->getClient()->getKeys($pattern);
    }

    /**
     * {@inheritDoc}
     * - Uses Redis `SCAN` command.
     *
     * @api
     *
     * @param string $pattern
     * @param int $limit
     * @param int|null $cursor
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, ?int $cursor = 0): StorageScanResultTransfer
    {
        return $this->getClient()->scanKeys($pattern, $limit, $cursor);
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
        $this->getClient()->resetAccessStats();
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
        return $this->getClient()->getAccessStats();
    }

    /**
     * {@inheritDoc}
     * - Uses Redis `DBSIZE` command.
     *
     * @api
     *
     * @return int
     */
    public function getCountItems(): int
    {
        return $this->getClient()->getDbSize();
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
        $this->getClient()->setDebug($debug);
    }
}
