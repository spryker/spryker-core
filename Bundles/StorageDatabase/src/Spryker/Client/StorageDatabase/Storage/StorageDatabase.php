<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage;

use Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface;
use Spryker\Client\StorageDatabaseExtension\Storage\Reader\StorageReaderInterface;

class StorageDatabase implements StorageDatabaseInterface
{
    protected const KEY_PLACEHOLDER = ':key';
    protected const KV_PREFIX = 'kv:';

    protected const ACCESS_STATS_KEY_COUNT = 'count';
    protected const ACCESS_STATS_KEY_KEYS = 'keys';
    protected const ACCESS_STATS_KEY_READ = 'read';

    /**
     * @var \Spryker\Client\StorageDatabaseExtension\Storage\Reader\StorageReaderInterface
     */
    protected $storageReader;

    /**
     * @var array
     */
    protected $accessStats;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var \Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\StorageDatabaseExtension\Storage\Reader\StorageReaderInterface $storageReader
     * @param \Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(StorageReaderInterface $storageReader, StorageDatabaseToUtilEncodingInterface $utilEncodingService)
    {
        $this->storageReader = $storageReader;
        $this->utilEncodingService = $utilEncodingService;
        $this->resetAccessStats();
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function get(string $key)
    {
        $result = $this->storageReader->get($key);
        $this->addReadAccessStats($key);

        $decodedResult = $this->decodeResult($result);

        return $decodedResult ?: null;
    }

    /**
     * @param string[] $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array
    {
        if (count($keys) === 0) {
            return [];
        }

        $results = array_combine($this->getPrefixedKeys($keys), $this->storageReader->getMulti($keys));
        $this->addMultiReadAccessStats($keys);

        return $results;
    }

    /**
     * @return void
     */
    public function resetAccessStats(): void
    {
        $this->accessStats = [
            static::ACCESS_STATS_KEY_COUNT => [
                static::ACCESS_STATS_KEY_READ => 0,
            ],
            static::ACCESS_STATS_KEY_KEYS => [
                static::ACCESS_STATS_KEY_READ => [],
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
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * @param string[] $keys
     *
     * @return string[]
     */
    protected function getPrefixedKeys(array $keys): array
    {
        $prefixedKeys = [];

        foreach ($keys as $key) {
            $prefixedKeys[] = $this->getPrefixedKeyName($key);
        }

        return $prefixedKeys;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getPrefixedKeyName(string $key): string
    {
        return sprintf('%s%s', static::KV_PREFIX, $key);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function addReadAccessStats(string $key): void
    {
        if ($this->debug) {
            $this->accessStats[static::ACCESS_STATS_KEY_COUNT][static::ACCESS_STATS_KEY_READ]++;
            $this->accessStats[static::ACCESS_STATS_KEY_KEYS][static::ACCESS_STATS_KEY_READ][] = $key;
        }
    }

    /**
     * @param string[] $keys
     *
     * @return void
     */
    protected function addMultiReadAccessStats(array $keys): void
    {
        if ($this->debug) {
            $this->accessStats[static::ACCESS_STATS_KEY_COUNT][static::ACCESS_STATS_KEY_READ] += count($keys);
            $this->accessStats[static::ACCESS_STATS_KEY_KEYS][static::ACCESS_STATS_KEY_READ] = array_unique(
                array_merge($this->accessStats[static::ACCESS_STATS_KEY_KEYS][static::ACCESS_STATS_KEY_READ], $keys)
            );
        }
    }

    /**
     * @param string $data
     *
     * @return mixed
     */
    protected function decodeResult(string $data)
    {
        $decodedResult = $this->utilEncodingService->decodeJson($data, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            return $data;
        }

        return $decodedResult;
    }
}
