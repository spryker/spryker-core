<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig\Cache\CacheLoader;

use Codeception\Test\Unit;
use Spryker\Shared\Twig\Cache\CacheLoader\FilesystemCacheLoader;
use Spryker\Shared\Twig\Cache\CacheLoaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group Cache
 * @group CacheLoader
 * @group FilesystemCacheLoaderTest
 * Add your own group annotations below this line
 */
class FilesystemCacheLoaderTest extends Unit
{
    /**
     * @return string
     */
    protected function getCacheFile()
    {
        $cacheDirectory = $this->getCacheDirectory();

        return $cacheDirectory . 'cache.php';
    }

    /**
     * @return string
     */
    protected function getCacheDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'test_files' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return void
     */
    public function testCanBeInstantiatedWithPathToCacheFile()
    {
        $cacheLoader = new FilesystemCacheLoader($this->getCacheFile());

        $this->assertInstanceOf(CacheLoaderInterface::class, $cacheLoader);
    }

    /**
     * @return void
     */
    public function testLoadReturnsEmptyArrayIfCacheFileNotPresent()
    {
        $cacheLoader = new FilesystemCacheLoader(__DIR__ . '/invalidFile');

        $this->assertCount(0, $cacheLoader->load());
    }

    /**
     * @return void
     */
    public function testLoadReturnsCacheArrayIfCacheFilePresent()
    {
        $cacheLoader = new FilesystemCacheLoader($this->getCacheFile());

        $this->assertNotCount(0, $cacheLoader->load());
    }
}
