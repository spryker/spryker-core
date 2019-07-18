<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Cache;

use Codeception\Test\Unit;
use Predis\Client as PredisClient;
use Spryker\Client\Storage\Cache\StorageCacheStrategyHelper;
use Spryker\Client\Storage\Redis\Service;
use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Storage\StorageConfig;
use SprykerTest\Client\Storage\Helper\CacheDataProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Storage
 * @group Cache
 * @group AbstractStorageCacheStrategyTest
 * Add your own group annotations below this line
 */
abstract class AbstractStorageCacheStrategyTest extends Unit
{
    public const TEST_CACHE_KEY = 'StorageClient_testKey';

    /**
     * @var \Spryker\Client\Storage\StorageClient
     */
    protected $storageClientMock;

    /**
     * @var \Spryker\Client\Storage\StorageConfig
     */
    protected $storageClientConfigMock;

    /**
     * @var \SprykerTest\Client\Storage\Helper\CacheDataProvider
     */
    protected $cacheDataProvider;

    /**
     * @var \Spryker\Client\Storage\Cache\StorageCacheStrategyHelperInterface
     */
    protected $storageCacheStrategyHelper;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->storageClientMock = $this
            ->getMockBuilder(StorageClient::class)
            ->setMethods(['getService'])
            ->getMock();

        $predisClientMock = $this
            ->getMockBuilder(PredisClient::class)
            ->getMock();

        $redisServiceMock = $this
            ->getMockBuilder(Service::class)
            ->setConstructorArgs([$predisClientMock])
            ->getMock();

        $this->storageClientConfigMock = $this
            ->getMockBuilder(StorageConfig::class)
            ->getMock();

        $this->cacheDataProvider = new CacheDataProvider($this->storageClientConfigMock);

        $this->storageCacheStrategyHelper = new StorageCacheStrategyHelper(
            $this->storageClientMock,
            $this->storageClientConfigMock
        );

        $this->storageClientMock
            ->method('getService')
            ->willReturn($redisServiceMock);

        $this->storageClientConfigMock
            ->method('getStorageCacheIncrementalStrategyKeySizeLimit')
            ->willReturn(100);
    }

    /**
     * @param string $testType
     *
     * @return void
     */
    abstract protected function testStrategy($testType);

    /**
     * @param string $testType
     *
     * @return void
     */
    protected function setCachedKeysByType($testType)
    {
        $this->storageClientMock->setCachedKeys($this->cacheDataProvider->getTestCacheDataInput($testType));
    }
}
