<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis\Redis\Iterator;

use Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface;
use Spryker\Client\StorageRedis\StorageRedisConfig;

class StorageRedisScanIterator implements StorageRedisScanIteratorInterface
{
    public const KV_PREFIX = 'kv:';

    /**
     * @var \Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface
     */
    protected $redisClient;

    /**
     * @var \Spryker\Client\StorageRedis\StorageRedisConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface $redisClient
     * @param \Spryker\Client\StorageRedis\StorageRedisConfig $config
     */
    public function __construct(StorageRedisToRedisClientInterface $redisClient, StorageRedisConfig $config)
    {
        $this->redisClient = $redisClient;
        $this->config = $config;

        $this->setupConnection();
    }

    /**
     * @param string $pattern
     * @param int $limit
     * @param int $cursor
     *
     * @return array [string, string[]]
     */
    public function scanKeys(string $pattern, int $limit, int $cursor): array
    {
        $result = [];
        $nextCursor = null;
        do {
            [$nextCursor, $keys] = $this->redisScan($this->getSearchPattern($pattern), $nextCursor ?? $cursor);
            $result = array_merge($result, $keys);
        } while ($nextCursor > 0 && count($result) < $limit);

        return [$cursor, array_unique($result)];
    }

    /**
     * @param string $match
     * @param int $cursor
     *
     * @return array [string, string[]]
     */
    protected function redisScan(string $match, int $cursor): array
    {
        return $this->redisClient->scan(
            $this->config->getRedisConnectionKey(),
            $cursor,
            [
                'COUNT' => $this->config->getRedisScanChunkSize(),
                'MATCH' => $match,
            ]
        );
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    protected function getSearchPattern(string $pattern): string
    {
        return static::KV_PREFIX . $pattern;
    }

    /**
     * @return void
     */
    protected function setupConnection(): void
    {
        $this->redisClient->setupConnection(
            $this->config->getRedisConnectionKey(),
            $this->config->getRedisConnectionConfiguration()
        );
    }
}
