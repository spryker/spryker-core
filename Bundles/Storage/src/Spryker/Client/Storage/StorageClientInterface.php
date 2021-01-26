<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Generated\Shared\Transfer\StorageScanResultTransfer;

/**
 * @method void persistCacheForRequest(\Symfony\Component\HttpFoundation\Request $request, $storageCacheStrategyName = \Spryker\Shared\Storage\StorageConstants::STORAGE_CACHE_STRATEGY_REPLACE)
 */
interface StorageClientInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function set($key, $value, $ttl = null);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function delete($key);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return int
     */
    public function deleteAll();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string[] $keys
     *
     * @return array
     */
    public function getMulti(array $keys);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return array
     */
    public function getStats();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return array
     */
    public function getAllKeys();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $pattern
     * @param int $limit
     * @param int|null $cursor
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, ?int $cursor = 0): StorageScanResultTransfer;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return void
     */
    public function resetAccessStats();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return array
     */
    public function getAccessStats();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return int
     */
    public function getCountItems();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Spryker\Client\Storage\Redis\ServiceInterface|\Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface|\Spryker\Client\StorageExtension\Dependency\Plugin\StorageScanPluginInterface
     */
    public function getService();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function setCachedKeys($keys);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return array
     */
    public function getCachedKeys();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $key
     *
     * @return void
     */
    public function unsetCachedKey($key);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return void
     */
    public function unsetLastCachedKey();
}
