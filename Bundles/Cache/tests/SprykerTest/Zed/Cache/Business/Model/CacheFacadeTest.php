<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cache\Business\Model;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cache
 * @group Business
 * @group Model
 * @group Facade
 * @group CacheFacadeTest
 * Add your own group annotations below this line
 */
class CacheFacadeTest extends Unit
{
    protected const TEST_CACHE_DIRECTORY_NAME = 'cache';
    protected const TEST_CODE_BUCKET_DIRECTORY_NAME = 'codeBucketDE';
    protected const TEST_DEFAULT_CODE_BUCKET_DIRECTORY_NAME = 'codeBucket';

    /**
     * @var \SprykerTest\Zed\Cache\CacheBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testEmptyCodeBucketCacheRemovesDirectory(): void
    {
        $structure = [
            static::TEST_CACHE_DIRECTORY_NAME => [
                static::TEST_CODE_BUCKET_DIRECTORY_NAME => [],
            ],
        ];

        //Arrange
        $cacheDirectory = $this->tester->getVirtualDirectory($structure);
        $path = sprintf('%s' . static::TEST_CACHE_DIRECTORY_NAME . DIRECTORY_SEPARATOR . static::TEST_CODE_BUCKET_DIRECTORY_NAME . DIRECTORY_SEPARATOR, $cacheDirectory);

        $this->tester->mockConfigMethod('getCodeBucketCachePath', $path);

        $this->assertTrue(is_dir($path));

        //Act
        $this->tester->getFacade()->emptyCodeBucketCache();

        //Assert
        $this->assertFalse(is_dir($path));
    }

    /**
     * @return void
     */
    public function testEmptyDefaultCodeBucketCacheRemovesDirectory(): void
    {
        $structure = [
            static::TEST_CACHE_DIRECTORY_NAME => [
                static::TEST_DEFAULT_CODE_BUCKET_DIRECTORY_NAME => [],
            ],
        ];

        //Arrange
        $cacheDirectory = $this->tester->getVirtualDirectory($structure);
        $path = sprintf('%s' . static::TEST_CACHE_DIRECTORY_NAME . DIRECTORY_SEPARATOR . static::TEST_DEFAULT_CODE_BUCKET_DIRECTORY_NAME . DIRECTORY_SEPARATOR, $cacheDirectory);

        $this->tester->mockConfigMethod('getDefaultCodeBucketCachePath', $path);

        $this->assertTrue(is_dir($path));

        //Act
        $this->tester->getFacade()->emptyDefaultCodeBucketCache();

        //Assert
        $this->assertFalse(is_dir($path));
    }
}
