<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Cache;

use Codeception\TestCase\Test;
use Predis\Client as PredisClient;
use Spryker\Client\Storage\Redis\Service;
use Spryker\Client\Storage\StorageClient;
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
abstract class AbstractStorageCacheStrategyTest extends Test
{

    const TEST_CACHE_KEY = 'StorageClient_testKey';

    /**
     * @var \SprykerTest\Zed\ProductLabel\BusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Client\Storage\StorageClient
     */
    protected $storageClientMock;

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

        $this->storageClientMock->method('getService')->willReturn($redisServiceMock);
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
        $this->storageClientMock->setCachedKeys(CacheDataProvider::getTestCacheDataInput($testType));
    }

}
