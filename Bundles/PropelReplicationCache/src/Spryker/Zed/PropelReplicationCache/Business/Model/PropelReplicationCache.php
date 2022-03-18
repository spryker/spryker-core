<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache\Business\Model;

use Spryker\Zed\PropelReplicationCache\Dependency\Client\PropelReplicationCacheToStorageRedisClientInterface;

class PropelReplicationCache implements PropelReplicationCacheInterface
{
    /**
     * @var \Spryker\Zed\PropelReplicationCache\Dependency\Client\PropelReplicationCacheToStorageRedisClientInterface
     */
    protected $storageRedisClient;

    /**
     * @var bool
     */
    protected $isReplicationEnabled = false;

    /**
     * @var int|null
     */
    protected $cacheTtl;

    /**
     * @param \Spryker\Zed\PropelReplicationCache\Dependency\Client\PropelReplicationCacheToStorageRedisClientInterface $storageRedisClient
     * @param bool $isReplicationEnabled
     * @param int $cacheTtl
     */
    public function __construct(
        PropelReplicationCacheToStorageRedisClientInterface $storageRedisClient,
        bool $isReplicationEnabled,
        int $cacheTtl
    ) {
        $this->storageRedisClient = $storageRedisClient;
        $this->isReplicationEnabled = $isReplicationEnabled;
        $this->cacheTtl = $cacheTtl;
    }

    /**
     * Normalizes Propel class name to simple object name and returns string key prepared to be saved.
     *
     * Example:
     *
     * $this->normalizeKey('Orm\\Zed\\Locale\\Persistence\\Base\\SpyLocaleQuery');
     * returns 'replication_SpyLocale'
     *
     * Example:
     *
     * $this->normalizeKey('Orm\\Zed\\Locale\\Persistence\\Base\\SpyLocale');
     * returns 'replication_SpyLocale'
     *
     * @param string $key
     *
     * @return string
     */
    protected function normalizeKey(string $key): string
    {
        $namespace = explode('\\', $key);
        $class = end($namespace);
        $result = str_replace('Query', '', $class);

        return sprintf('replication_%s', $result);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $key
     * @param int|null $ttl
     *
     * @return void
     */
    public function setKey(string $key, ?int $ttl = null): void
    {
        if (!$this->isReplicationEnabled) {
            return;
        }

        $key = $this->normalizeKey($key);
        $ttl = $ttl ?? $this->cacheTtl;

        $this->storageRedisClient->set($key, '', $ttl);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        if (!$this->isReplicationEnabled) {
            return false;
        }

        $key = $this->normalizeKey($key);
        $data = $this->storageRedisClient->get($key);

        return ($data !== null);
    }
}
