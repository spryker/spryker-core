<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Cache;

use Spryker\Client\Storage\Cache\StorageCacheIncrementalStrategy;
use SprykerTest\Client\Storage\Helper\CacheDataProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Storage
 * @group Cache
 * @group StorageCacheIncrementalStrategyTest
 * Add your own group annotations below this line
 */
class StorageCacheIncrementalStrategyTest extends AbstractStorageCacheStrategyTest
{
    /**
     * @param string $testType
     *
     * @return void
     */
    protected function testStrategy(string $testType): void
    {
        $this->setCachedKeysByType($testType);

        $incrementalStrategy = new StorageCacheIncrementalStrategy(
            $this->storageCacheStrategyHelper,
            $this->storageClientConfigMock
        );
        $incrementalStrategy->updateCache(self::TEST_CACHE_KEY);

        $expectedOutput = $this->cacheDataProvider->getExpectedOutputForIncrementalStrategy($testType);
        $this->assertSame(
            $this->storageClientMock->getCachedKeys(),
            $expectedOutput
        );
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithNewKeys(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NEW_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithUsedKeys(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithUnusedKeys(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_UNUSED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithNewAndUsedKeys(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NEW_AND_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithNewAndUsedAndUnusedKeys(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NEW_AND_USED_AND_UNUSED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithNoKeys(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NO_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithAllKeysAreNew(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithAllKeysAreUsed(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithAllKeysAreUnused(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithKeysAreNewAndUsed(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithKeysAreNewAndUsedAndUnused(): void
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS);
    }
}
