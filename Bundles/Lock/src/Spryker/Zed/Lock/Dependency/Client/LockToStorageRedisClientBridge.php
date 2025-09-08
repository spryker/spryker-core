<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Lock\Dependency\Client;

class LockToStorageRedisClientBridge implements LockToStorageRedisClientInterface
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
     * @param string $script
     * @param int $numKeys
     * @param mixed ...$keysOrArgs
     *
     * @return bool
     */
    public function evaluate(string $script, int $numKeys, ...$keysOrArgs): bool
    {
        return $this->storageRedisClient->evaluate($script, $numKeys, ...$keysOrArgs);
    }
}
