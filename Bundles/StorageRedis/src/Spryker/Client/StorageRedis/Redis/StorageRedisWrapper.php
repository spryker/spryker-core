<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageRedis\Redis;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\StorageScanResultTransfer;
use Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface;
use Spryker\Client\StorageRedis\Exception\StorageRedisException;
use Spryker\Client\StorageRedis\StorageRedisConfig;

class StorageRedisWrapper implements StorageRedisWrapperInterface
{
    /**
     * @var string
     */
    public const KV_PREFIX = 'kv:';

    /**
     * @var \Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface
     */
    protected $redisClient;

    /**
     * @var \Spryker\Client\StorageRedis\StorageRedisConfig
     */
    protected $storageRedisConfig;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var array
     */
    protected $accessStats;

    /**
     * @var string
     */
    protected $connectionKey;

    /**
     * @param \Spryker\Client\StorageRedis\Dependency\Client\StorageRedisToRedisClientInterface $redisClient
     * @param \Spryker\Client\StorageRedis\StorageRedisConfig $storageRedisConfig
     */
    public function __construct(
        StorageRedisToRedisClientInterface $redisClient,
        StorageRedisConfig $storageRedisConfig
    ) {
        $this->redisClient = $redisClient;
        $this->storageRedisConfig = $storageRedisConfig;
        $this->debug = $this->storageRedisConfig->getDebugMode();
        $this->connectionKey = $this->storageRedisConfig->getRedisConnectionKey();

        $this->resetAccessStats();
        $this->setupConnection($this->storageRedisConfig->getRedisConnectionConfiguration());
    }

    /**
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * Sets, reads, writes stats array
     *
     * @return void
     */
    public function resetAccessStats(): void
    {
        $this->accessStats = [
            'count' => [
                'read' => 0,
                'write' => 0,
                'delete' => 0,
            ],
            'keys' => [
                'read' => [],
                'write' => [],
                'delete' => [],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAccessStats(): array
    {
        return $this->accessStats;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        $key = $this->getKeyName($key);
        $value = $this->redisClient->get($this->connectionKey, $key);
        $this->addReadAccessStats($key);

        $result = json_decode((string)$value, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            return $value;
        }

        return $result;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array
    {
        if (count($keys) === 0) {
            return $keys;
        }

        $transformedKeys = [];
        foreach ($keys as $key) {
            $transformedKeys[] = $this->getKeyName($key);
        }

        $values = array_combine($transformedKeys, $this->redisClient->mget($this->connectionKey, $transformedKeys)) ?: [];
        $this->addMultiReadAccessStats($keys);

        return $values;
    }

    /**
     * @param string|null $section
     *
     * @return array
     */
    public function getStats(?string $section = null): array
    {
        return $this->redisClient->info($this->connectionKey, $section);
    }

    /**
     * @return array
     */
    public function getAllKeys(): array
    {
        return $this->getKeys('*');
    }

    /**
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys(string $pattern): array
    {
        return $this->redisClient->keys($this->connectionKey, $this->getSearchPattern($pattern));
    }

    /**
     * @param string $pattern
     * @param int $limit
     * @param int $cursor
     *
     * @return \Generated\Shared\Transfer\StorageScanResultTransfer
     */
    public function scanKeys(string $pattern, int $limit, int $cursor): StorageScanResultTransfer
    {
        $result = [];
        $nextCursor = null;
        do {
            [$nextCursor, $keys] = $this->redisScan($this->getSearchPattern($pattern), $nextCursor ?? $cursor);
            $result = $this->concatWithLimit($result, $keys, $limit);
        } while ($nextCursor > 0 && count($result) < $limit);

        return (new StorageScanResultTransfer())
            ->setCursor((int)$nextCursor)
            ->setKeys(array_unique($result));
    }

    /**
     * @param array<string> $keys1
     * @param array<string> $keys2
     * @param int|null $limit
     *
     * @return array<string>
     */
    protected function concatWithLimit(array $keys1, array $keys2, ?int $limit = null): array
    {
        if ($limit === null) {
            return array_merge($keys1, $keys2);
        }

        return array_merge($keys1, array_slice($keys2, 0, $limit - count($keys1)));
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
            $this->connectionKey,
            $cursor,
            [
                'COUNT' => $this->storageRedisConfig->getRedisScanChunkSize(),
                'MATCH' => $match,
            ],
        );
    }

    /**
     * @return int
     */
    public function getCountItems(): int
    {
        return count($this->redisClient->keys($this->connectionKey, $this->getSearchPattern()));
    }

    /**
     * @return int
     */
    public function getDbSize(): int
    {
        return $this->redisClient->dbSize($this->connectionKey);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @throws \Spryker\Client\StorageRedis\Exception\StorageRedisException
     *
     * @return bool
     */
    public function set(string $key, $value, ?int $ttl = null): bool
    {
        $key = $this->getKeyName($key);

        if ($ttl === null) {
            $result = $this->redisClient->set($this->connectionKey, $key, $value);
        } else {
            $result = $this->redisClient->setex($this->connectionKey, $key, $ttl, $value);
        }

        $this->addWriteAccessStats($key);
        if (!$result) {
            throw new StorageRedisException(
                sprintf('Could not set redisKey: "%s" with value: "%s"', $key, json_encode($value)),
            );
        }

        return $result;
    }

    /**
     * @param array $items
     *
     * @throws \Spryker\Client\StorageRedis\Exception\StorageRedisException
     *
     * @return void
     */
    public function setMulti(array $items): void
    {
        $data = [];

        foreach ($items as $key => $value) {
            $dataKey = $this->getKeyName($key);

            if (!is_scalar($value)) {
                $value = json_encode($value);
            }

            $data[$dataKey] = $value;
        }

        if (count($data) === 0) {
            return;
        }

        $result = $this->redisClient->mset($this->connectionKey, $data);
        $this->addMultiWriteAccessStats($data);

        if (!$result) {
            throw new StorageRedisException(
                sprintf(
                    'Could not set redisKeys for items: "[%s]" with values: "[%s]"',
                    implode(',', array_keys($items)),
                    implode(',', array_values($items)),
                ),
            );
        }
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function delete(string $key): int
    {
        $key = $this->getKeyName($key);
        $result = $this->redisClient->del($this->connectionKey, [$key]);
        $this->addDeleteAccessStats($key);

        return $result;
    }

    /**
     * @param array $keys
     *
     * @return int
     */
    public function deleteMulti(array $keys): int
    {
        if (count($keys) === 0) {
            return 0;
        }

        $transformedKeys = [];
        foreach ($keys as $key) {
            $transformedKeys[] = $this->getKeyName($key);
        }

        $result = $this->redisClient->del($this->connectionKey, $transformedKeys);
        $this->addMultiDeleteAccessStats($transformedKeys);

        return $result;
    }

    /**
     * @return int
     */
    public function deleteAll(): int
    {
        $dbSizeBefore = $this->getDbSize();
        $this->redisClient->flushDb($this->connectionKey);
        $dbSizeAfter = $this->getDbSize();

        return $dbSizeBefore - $dbSizeAfter;
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
        return $this->redisClient->eval($this->connectionKey, $script, $numKeys, ...$keysOrArgs);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function addReadAccessStats(string $key): void
    {
        if ($this->debug) {
            $this->accessStats['count']['read']++;
            $this->accessStats['keys']['read'][] = $key;
        }
    }

    /**
     * @param array $items
     *
     * @return void
     */
    protected function addMultiWriteAccessStats(array $items): void
    {
        if ($this->debug) {
            $this->accessStats['count']['write'] += count($items);
            $this->accessStats['keys']['write'] = $this->accessStats['keys']['write'] + array_keys($items);
        }
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function addDeleteAccessStats(string $key): void
    {
        if ($this->debug) {
            $this->accessStats['count']['delete']++;
            $this->accessStats['keys']['delete'][] = $key;
        }
    }

    /**
     * @param array $keys
     *
     * @return void
     */
    protected function addMultiDeleteAccessStats(array $keys): void
    {
        if ($this->debug) {
            $this->accessStats['count']['delete'] += count($keys);
            $this->accessStats['keys']['delete'] = $this->accessStats['keys']['delete'] + $keys;
        }
    }

    /**
     * @param array $keys
     *
     * @return void
     */
    protected function addMultiReadAccessStats(array $keys): void
    {
        if ($this->debug) {
            $this->accessStats['count']['read'] += count($keys);
            $this->accessStats['keys']['read'] = $this->accessStats['keys']['read'] + $keys;
        }
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function addWriteAccessStats(string $key): void
    {
        if ($this->debug) {
            $this->accessStats['count']['write']++;
            $this->accessStats['keys']['write'][] = $key;
        }
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    protected function getSearchPattern(string $pattern = '*'): string
    {
        return static::KV_PREFIX . $pattern;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getKeyName(string $key): string
    {
        return static::KV_PREFIX . $key;
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer $configurationTransfer
     *
     * @return void
     */
    protected function setupConnection(RedisConfigurationTransfer $configurationTransfer): void
    {
        $this->redisClient->setupConnection(
            $this->connectionKey,
            $configurationTransfer,
        );
    }
}
