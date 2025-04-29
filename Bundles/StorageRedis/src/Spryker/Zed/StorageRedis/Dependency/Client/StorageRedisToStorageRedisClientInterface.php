<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Dependency\Client;

use Generated\Shared\Transfer\StorageScanResultTransfer;

interface StorageRedisToStorageRedisClientInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return bool
     */
    public function set(string $key, string $value, ?int $ttl = null): bool;

    /**
     * @param string $pattern
     * @param int $limit
     * @param int $cursor
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, int $cursor): StorageScanResultTransfer;
}
