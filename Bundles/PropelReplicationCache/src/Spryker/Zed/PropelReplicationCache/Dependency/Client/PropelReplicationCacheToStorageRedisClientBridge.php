<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache\Dependency\Client;

class PropelReplicationCacheToStorageRedisClientBridge implements PropelReplicationCacheToStorageRedisClientInterface
{
    /**
     * @var \Spryker\Client\StorageRedis\StorageRedisClientInterface
     */
    protected $storageRedisClient;

    /**
     * @param \Spryker\Client\StorageRedis\StorageRedisClientInterface $storageRedisClient
     */
    public function __construct($storageRedisClient)
    {
        $this->storageRedisClient = $storageRedisClient;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->storageRedisClient->get($key);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int|null $expireTTL
     *
     * @return bool
     */
    public function set(string $key, string $value, ?int $expireTTL = null): bool
    {
        return $this->storageRedisClient->set($key, $value, $expireTTL);
    }
}
