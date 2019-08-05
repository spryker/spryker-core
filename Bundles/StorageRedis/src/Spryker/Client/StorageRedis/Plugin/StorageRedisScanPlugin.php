<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis\Plugin;

use Spryker\Client\StorageExtension\Dependency\Plugin\StorageScanPluginInterface;

/**
 * - The methods `scanKeys()` and `getCountItems()` uses Redis `SCAN` and `DBSIZE` commands.
 * - `SCAN` offers limited guarantees about the returned elements because it's non-blocking command.
 * - `DBSIZE` will return the correct storage items count if you are using separate database for storage.
 *
 * @method \Spryker\Client\StorageRedis\StorageRedisFactory getFactory()
 * @method \Spryker\Client\StorageRedis\StorageRedisConfig getConfig()
 * @method \Spryker\Client\StorageRedis\StorageRedisClientInterface getClient()
 */
class StorageRedisScanPlugin extends StorageRedisPlugin implements StorageScanPluginInterface
{
    /**
     * {@inheritdoc}
     * - Uses Redis `SCAN` command.
     *
     * @api
     *
     * @param string $pattern
     * @param int $limit
     * @param int|null $cursor
     *
     * @return array [int, string[]]
     */
    public function scanKeys(string $pattern, int $limit, ?int $cursor = 0): array
    {
        return $this->getClient()->scanKeys($pattern, $limit, $cursor);
    }

    /**
     * {@inheritdoc}
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
}
