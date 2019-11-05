<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis\Redis;

use Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface;
use Spryker\Client\StorageRedis\StorageRedisConfig;

class StorageRedisWrapperFactory implements StorageRedisWrapperFactoryInterface
{
    /**
     * @var \Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface
     */
    protected $redisClient;

    /**
     * @var \Spryker\Client\StorageRedis\StorageRedisConfig
     */
    protected $config;

    /**
     * @var \Spryker\Client\StorageRedis\Redis\StorageRedisWrapperInterface
     */
    protected static $storageRedisWrapper;

    /**
     * @param \Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface $redisClient
     * @param \Spryker\Client\StorageRedis\StorageRedisConfig $config
     */
    public function __construct(StorageRedisToRedisClientInterface $redisClient, StorageRedisConfig $config)
    {
        $this->redisClient = $redisClient;
        $this->config = $config;
    }

    /**
     * @return \Spryker\Client\StorageRedis\Redis\StorageRedisWrapperInterface
     */
    public function createStorageRedisWrapper(): StorageRedisWrapperInterface
    {
        if (static::$storageRedisWrapper === null) {
            static::$storageRedisWrapper = new StorageRedisWrapper(
                $this->redisClient,
                $this->config
            );
        }

        return static::$storageRedisWrapper;
    }
}
