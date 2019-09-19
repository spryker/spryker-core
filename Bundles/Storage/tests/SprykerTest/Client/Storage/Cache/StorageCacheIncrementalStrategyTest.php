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
    protected function testStrategy($testType)
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
    public function testIncrementalStrategyWithNewKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NEW_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithUsedKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithUnusedKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_UNUSED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithNewAndUsedKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NEW_AND_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithNewAndUsedAndUnusedKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NEW_AND_USED_AND_UNUSED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyWithNoKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NO_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithAllKeysAreNew()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_NEW_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithAllKeysAreUsed()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithAllKeysAreUnused()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_UNUSED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithKeysAreNewAndUsed()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testIncrementalStrategyOverLimitWithKeysAreNewAndUsedAndUnused()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_OVER_LIMIT_WITH_NEW_AND_USED_AND_UNUSED_KEYS);
    }
}
