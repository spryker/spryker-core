<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Helper;

use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Storage\StorageConfig;

class CacheDataProvider
{
    public const TEST_TYPE_NEW_KEYS = 'TEST_TYPE_NEW_CACHE';
    public const TEST_TYPE_USED_KEYS = 'TEST_TYPE_USED_KEYS';
    public const TEST_TYPE_UNUSED_KEYS = 'TEST_TYPE_UNUSED_KEYS';
    public const TEST_TYPE_NEW_AND_USED_KEYS = 'TEST_TYPE_NEW_AND_USED_KEYS';
    public const TEST_TYPE_NEW_AND_USED_AND_UNUSED_KEYS = 'TEST_TYPE_NEW_AND_USED_AND_UNUSED_KEYS';
    public const TEST_TYPE_NO_KEYS = 'TEST_TYPE_NO_KEYS';
    public const TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS = 'TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS';
    public const TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS = 'TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS';
    public const TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS = 'TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS';
    public const TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS = 'TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS';
    public const TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS = 'TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS';
    public const OVER_LIMIT_SIZE = 50;

    /**
     * @var \Spryker\Client\Storage\StorageConfig
     */
    protected $storageClientConfigMock;

    /**
     * @param \Spryker\Client\Storage\StorageConfig $storageClientConfigMock
     */
    public function __construct(StorageConfig $storageClientConfigMock)
    {
        $this->storageClientConfigMock = $storageClientConfigMock;
    }

    /**
     * @param string $testType
     *
     * @return array
     */
    public function getTestCacheDataInput($testType)
    {
        $cacheData = [];

        switch ($testType) {
            case self::TEST_TYPE_NEW_KEYS:
                $cacheData = [
                    'kv:key1' => StorageClient::KEY_NEW,
                    'kv:key2' => StorageClient::KEY_NEW,
                    'kv:key3' => StorageClient::KEY_NEW,
                ];
                break;

            case self::TEST_TYPE_USED_KEYS:
                $cacheData = [
                    'kv:key1' => StorageClient::KEY_USED,
                    'kv:key2' => StorageClient::KEY_USED,
                    'kv:key3' => StorageClient::KEY_USED,
                ];
                break;

            case self::TEST_TYPE_UNUSED_KEYS:
                $cacheData = [
                    'kv:key1' => StorageClient::KEY_INIT,
                    'kv:key2' => StorageClient::KEY_INIT,
                    'kv:key3' => StorageClient::KEY_INIT,
                ];
                break;

            case self::TEST_TYPE_NEW_AND_USED_KEYS:
                $cacheData = [
                    'kv:key1' => StorageClient::KEY_NEW,
                    'kv:key2' => StorageClient::KEY_USED,
                    'kv:key3' => StorageClient::KEY_USED,
                ];
                break;

            case self::TEST_TYPE_NEW_AND_USED_AND_UNUSED_KEYS:
                $cacheData = [
                    'kv:key1' => StorageClient::KEY_NEW,
                    'kv:key2' => StorageClient::KEY_USED,
                    'kv:key3' => StorageClient::KEY_INIT,
                ];
                break;

            case self::TEST_TYPE_NO_KEYS:
                $cacheData = [];
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS:
                $cacheData = self::generateOverLimitCacheInput(self::TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS);
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS:
                $cacheData = self::generateOverLimitCacheInput(self::TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS);
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS:
                $cacheData = self::generateOverLimitCacheInput(self::TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS);
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS:
                $cacheData = self::generateOverLimitCacheInput(self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS);
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS:
                $cacheData = self::generateOverLimitCacheInput(self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS);
                break;
        }

        return $cacheData;
    }

    /**
     * @param string $testType
     *
     * @return array
     */
    public function getExpectedOutputForReplaceStrategy($testType)
    {
        $expectedOutput = [];

        switch ($testType) {
            case self::TEST_TYPE_NEW_KEYS:
                $expectedOutput = [
                    'kv:key1' => StorageClient::KEY_NEW,
                    'kv:key2' => StorageClient::KEY_NEW,
                    'kv:key3' => StorageClient::KEY_NEW,
                ];
                break;

            case self::TEST_TYPE_USED_KEYS:
                $expectedOutput = [
                    'kv:key1' => StorageClient::KEY_USED,
                    'kv:key2' => StorageClient::KEY_USED,
                    'kv:key3' => StorageClient::KEY_USED,
                ];
                break;

            case self::TEST_TYPE_UNUSED_KEYS:
                $expectedOutput = [];
                break;

            case self::TEST_TYPE_NEW_AND_USED_KEYS:
                $expectedOutput = [
                    'kv:key1' => StorageClient::KEY_NEW,
                    'kv:key2' => StorageClient::KEY_USED,
                    'kv:key3' => StorageClient::KEY_USED,
                ];
                break;

            case self::TEST_TYPE_NEW_AND_USED_AND_UNUSED_KEYS:
                $expectedOutput = [
                    'kv:key1' => StorageClient::KEY_NEW,
                    'kv:key2' => StorageClient::KEY_USED,
                ];
                break;

            case self::TEST_TYPE_NO_KEYS:
                $expectedOutput = [];
                break;
        }

        return $expectedOutput;
    }

    /**
     * @param string $testType
     *
     * @return array
     */
    public function getExpectedOutputForIncrementalStrategy($testType)
    {
        $expectedOutput = [];

        switch ($testType) {
            case self::TEST_TYPE_NEW_KEYS:
                $expectedOutput = [
                    'kv:key1' => StorageClient::KEY_NEW,
                    'kv:key2' => StorageClient::KEY_NEW,
                    'kv:key3' => StorageClient::KEY_NEW,
                ];
                break;

            case self::TEST_TYPE_USED_KEYS:
                $expectedOutput = [
                    'kv:key1' => StorageClient::KEY_USED,
                    'kv:key2' => StorageClient::KEY_USED,
                    'kv:key3' => StorageClient::KEY_USED,
                ];
                break;

            case self::TEST_TYPE_UNUSED_KEYS:
                $expectedOutput = [
                    'kv:key1' => StorageClient::KEY_INIT,
                    'kv:key2' => StorageClient::KEY_INIT,
                    'kv:key3' => StorageClient::KEY_INIT,
                ];
                break;

            case self::TEST_TYPE_NEW_AND_USED_KEYS:
                $expectedOutput = [
                    'kv:key1' => StorageClient::KEY_NEW,
                    'kv:key2' => StorageClient::KEY_USED,
                    'kv:key3' => StorageClient::KEY_USED,
                ];
                break;

            case self::TEST_TYPE_NEW_AND_USED_AND_UNUSED_KEYS:
                $expectedOutput = [
                    'kv:key1' => StorageClient::KEY_NEW,
                    'kv:key2' => StorageClient::KEY_USED,
                    'kv:key3' => StorageClient::KEY_INIT,
                ];
                break;

            case self::TEST_TYPE_NO_KEYS:
                $expectedOutput = [];
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS:
                $expectedOutput = self::generateOverLimitCacheOutput(self::TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS);
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS:
                $expectedOutput = self::generateOverLimitCacheOutput(self::TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS);
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS:
                $expectedOutput = self::generateOverLimitCacheOutput(self::TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS);
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS:
                $expectedOutput = self::generateOverLimitCacheOutput(self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS);
                break;

            case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS:
                $expectedOutput = self::generateOverLimitCacheOutput(self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS);
                break;
        }

        return $expectedOutput;
    }

    /**
     * @param string $testType
     *
     * @return array
     */
    private function generateOverLimitCacheInput($testType)
    {
        $cache = [];
        $cacheSize = $this->storageClientConfigMock->getStorageCacheIncrementalStrategyKeySizeLimit();
        $cacheSizeWithOverLimit = $cacheSize + self::OVER_LIMIT_SIZE;

        for ($i = 0; $i < $cacheSizeWithOverLimit; $i++) {
            $key = 'kv:key' . ($i + 1);
            switch ($testType) {
                case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS:
                    $value = StorageClient::KEY_NEW;
                    break;

                case self::TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS:
                    $value = StorageClient::KEY_USED;
                    break;

                case self::TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS:
                    $value = StorageClient::KEY_INIT;
                    break;

                case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS:
                    // Dividing dataset into two halves: used keys then new keys
                    $halfDataset = ceil($cacheSize / 2);
                    $value = $i < $halfDataset ? StorageClient::KEY_USED : StorageClient::KEY_NEW;
                    break;

                case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS:
                    // Dividing dataset into three thirds: unused keys then used keys then new keys
                    $firstThirdDataset = ceil($cacheSize / 3);
                    $secondThirdDataset = $firstThirdDataset * 2;
                    $value = $i < $firstThirdDataset ? StorageClient::KEY_INIT :
                        ($i < $secondThirdDataset ? StorageClient::KEY_USED : StorageClient::KEY_NEW);
                    break;

                default:
                    $value = StorageClient::KEY_NEW;
                    break;
            }

            $cache[$key] = $value;
        }

        return $cache;
    }

    /**
     * @param string $testType
     *
     * @return array
     */
    private function generateOverLimitCacheOutput($testType)
    {
        $cache = [];
        $cacheSize = $this->storageClientConfigMock->getStorageCacheIncrementalStrategyKeySizeLimit();

        for ($i = 0; $i < $cacheSize; $i++) {
            $key = 'kv:key' . ($i + 1);
            switch ($testType) {
                case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS:
                    $value = StorageClient::KEY_NEW;
                    break;

                case self::TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS:
                    $value = StorageClient::KEY_USED;
                    break;

                case self::TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS:
                    $key = 'kv:key' . ($i + self::OVER_LIMIT_SIZE + 1);
                    $value = StorageClient::KEY_INIT;
                    break;

                case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS:
                    $halfDataset = ceil($cacheSize / 2);
                    $value = $i < $halfDataset ? StorageClient::KEY_USED : StorageClient::KEY_NEW;
                    break;

                case self::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS:
                    $thirdDataset = ceil($cacheSize / 3);
                    if ($thirdDataset <= self::OVER_LIMIT_SIZE) {
                        // When all unused keys over limit, the result does not contain unused keys
                        $key = 'kv:key' . ($i + $thirdDataset + 1);
                        $value = $i < $thirdDataset ? StorageClient::KEY_USED : StorageClient::KEY_NEW;
                    } else {
                        // When there are unused keys within the limit, the result contains the rest of the unused keys after removing the over limit ones
                        $inLimitUnusedKeysSize = $thirdDataset - self::OVER_LIMIT_SIZE;
                        $key = 'kv:key' . ($i + $thirdDataset - $inLimitUnusedKeysSize + 1);
                        $value = $i < $inLimitUnusedKeysSize ? StorageClient::KEY_INIT :
                            ($i < $inLimitUnusedKeysSize + $thirdDataset ? StorageClient::KEY_USED : StorageClient::KEY_NEW);
                    }
                    break;

                default:
                    $value = StorageClient::KEY_NEW;
                    break;
            }

            $cache[$key] = $value;
        }

        return $cache;
    }
}
