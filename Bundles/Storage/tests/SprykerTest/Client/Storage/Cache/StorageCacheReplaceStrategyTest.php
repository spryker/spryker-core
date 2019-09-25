<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Cache;

use Spryker\Client\Storage\Cache\StorageCacheReplaceStrategy;
use SprykerTest\Client\Storage\Helper\CacheDataProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Storage
 * @group Cache
 * @group StorageCacheReplaceStrategyTest
 * Add your own group annotations below this line
 */
class StorageCacheReplaceStrategyTest extends AbstractStorageCacheStrategyTest
{
    /**
     * @param string $testType
     *
     * @return void
     */
    protected function testStrategy($testType)
    {
        $this->setCachedKeysByType($testType);

        $replaceStrategy = new StorageCacheReplaceStrategy(
            $this->storageCacheStrategyHelper
        );
        $replaceStrategy->updateCache(self::TEST_CACHE_KEY);

        $expectedOutput = $this->cacheDataProvider->getExpectedOutputForReplaceStrategy($testType);
        $this->assertSame(
            $this->storageClientMock->getCachedKeys(),
            $expectedOutput
        );
    }

    /**
     * @return void
     */
    public function testReplaceStrategyWithNewKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NEW_KEYS);
    }

    /**
     * @return void
     */
    public function testReplaceStrategyWithUsedKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testReplaceStrategyWithUnusedKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_UNUSED_KEYS);
    }

    /**
     * @return void
     */
    public function testReplaceStrategyWithNewAndUsedKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NEW_AND_USED_KEYS);
    }

    /**
     * @return void
     */
    public function testReplaceStrategyWithNewAndUsedAndUnusedKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NEW_AND_USED_AND_UNUSED_KEYS);
    }

    /**
     * @return void
     */
    public function testReplaceStrategyWithNoKeys()
    {
        $this->testStrategy(CacheDataProvider::TEST_TYPE_NO_KEYS);
    }
}
