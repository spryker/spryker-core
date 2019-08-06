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
     * @api
     *
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items);

    /**
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function delete($key);

    /**
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys);

    /**
     * @api
     *
     * @return int
     */
    public function deleteAll();

    /**
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys);

    /**
     * @api
     *
     * @return array
     */
    public function getStats();

    /**
     * @api
     *
     * @return array
     */
    public function getAllKeys();

    /**
     * @api
     *
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys($pattern);

    /**
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
     * @api
     *
     * @return void
     */
    public function resetAccessStats();

    /**
     * @api
     *
     * @return array
     */
    public function getAccessStats();

    /**
     * @api
     *
     * @return int
     */
    public function getCountItems();

    /**
     * @api
     *
     * @return \Spryker\Client\Storage\StorageClientInterface $service
     */
    public function getService();

    /**
     * @api
     *
     * @param array $keys
     *
     * @return array
     */
    public function setCachedKeys($keys);

    /**
     * @api
     *
     * @return array
     */
    public function getCachedKeys();

    /**
     * @api
     *
     * @param string $key
     *
     * @return void
     */
    public function unsetCachedKey($key);

    /**
     * @api
     *
     * @return void
     */
    public function unsetLastCachedKey();
}
