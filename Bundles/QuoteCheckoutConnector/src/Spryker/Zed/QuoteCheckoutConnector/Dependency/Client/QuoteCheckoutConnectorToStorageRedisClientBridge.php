<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteCheckoutConnector\Dependency\Client;

class QuoteCheckoutConnectorToStorageRedisClientBridge implements QuoteCheckoutConnectorToStorageRedisClientInterface
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
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->storageRedisClient->get($key);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return mixed
     */
    public function set(string $key, string $value, ?int $ttl = null)
    {
        return $this->storageRedisClient->set($key, $value, $ttl);
    }
}
