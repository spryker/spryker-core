<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage;

use Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface;
use Spryker\Client\StorageDatabase\Exception\MissingStorageReaderPluginException;
use Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface;

class StorageDatabase implements StorageDatabaseInterface
{
    protected const KEY_PLACEHOLDER = ':key';
    protected const KV_PREFIX = 'kv:';

    protected const ACCESS_STATS_KEY_COUNT = 'count';
    protected const ACCESS_STATS_KEY_KEYS = 'keys';
    protected const ACCESS_STATS_KEY_READ = 'read';

    /**
     * @var \Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface|null
     */
    protected $storageReaderPlugin;

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
     * @param \Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface|null $storageReaderPlugin
     */
    public function __construct(StorageDatabaseToUtilEncodingInterface $utilEncodingService, ?StorageReaderPluginInterface $storageReaderPlugin = null)
    {
        $this->utilEncodingService = $utilEncodingService;
        $this->storageReaderPlugin = $storageReaderPlugin;
        $this->resetAccessStats();
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function get(string $key)
    {
        $result = $this->getStorageReaderPlugin()->get($key);
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

        $results = array_combine($this->getPrefixedKeys($keys), $this->getStorageReaderPlugin()->getMulti($keys));
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
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return void
     */
    public function set(string $key, string $value, ?int $ttl = null): void
    {
    }

    /**
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items): void
    {
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function delete(string $key): int
    {
        return 0;
    }

    /**
     * @param array $keys
     *
     * @return int
     */
    public function deleteMulti(array $keys): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function deleteAll(): int
    {
        return 0;
    }

    /**
     * @return array
     */
    public function getStats(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAllKeys(): array
    {
        return [];
    }

    /**
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys(string $pattern): array
    {
        return [];
    }

    /**
     * @return int
     */
    public function getCountItems(): int
    {
        return 0;
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

    /**
     * @throws \Spryker\Client\StorageDatabase\Exception\MissingStorageReaderPluginException
     *
     * @return \Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface
     */
    protected function getStorageReaderPlugin(): StorageReaderPluginInterface
    {
        if (!$this->storageReaderPlugin) {
            throw new MissingStorageReaderPluginException(
                sprintf(
                    'There is no registered plugin which can perform storage database interaction.
                    Make sure that StorageDatabaseDependencyProvider::getStorageReaderProviderPlugin() returns
                    an implementation of %s',
                    StorageReaderPluginInterface::class
                )
            );
        }
        
        return $this->storageReaderPlugin;
    }
}
