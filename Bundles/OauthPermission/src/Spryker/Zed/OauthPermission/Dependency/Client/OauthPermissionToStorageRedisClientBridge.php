<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthPermission\Dependency\Client;

class OauthPermissionToStorageRedisClientBridge implements OauthPermissionToStorageRedisClientInterface
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
     * @param string $value
     * @param int|null $ttl
     *
     * @return bool
     */
    public function set(string $key, string $value, ?int $ttl = null): bool
    {
        return $this->storageRedisClient->set($key, $value, $ttl);
    }
}
