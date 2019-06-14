<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage;

use Spryker\Client\StorageDatabase\Storage\Reader\AbstractStorageReader;

class StorageDatabase implements StorageDatabaseInterface
{
    protected const KEY_PLACEHOLDER = ':key';
    protected const KV_PREFIX = 'kv:';

    /**
     * @var \Spryker\Client\StorageDatabase\Storage\Reader\AbstractStorageReader
     */
    protected $storageReader;

    /**
     * @var array
     */
    protected $accessStats = [
        'count' => [
            'read' => 0,
        ],
        'keys' => [
            'read' => [],
        ],
    ];

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @param \Spryker\Client\StorageDatabase\Storage\Reader\AbstractStorageReader $storageReader
     */
    public function __construct(AbstractStorageReader $storageReader)
    {
        $this->storageReader = $storageReader;
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
            'count' => [
                'read' => 0,
            ],
            'keys' => [
                'read' => [],
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
        return self::KV_PREFIX . $key;
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
     * @param string[] $keys
     *
     * @return void
     */
    protected function addMultiReadAccessStats(array $keys): void
    {
        if ($this->debug) {
            $this->accessStats['count']['read'] += count($keys);
            $this->accessStats['keys']['read'] = array_unique(
                array_merge($this->accessStats['keys']['read'], $keys)
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
        $decodedResult = json_decode($data, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            return $data;
        }

        return $decodedResult;
    }
}
