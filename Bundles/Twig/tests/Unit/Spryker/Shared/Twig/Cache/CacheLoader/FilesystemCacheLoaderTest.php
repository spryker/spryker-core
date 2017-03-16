<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Twig\Cache\CacheLoader;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Twig\Cache\CacheLoaderInterface;
use Spryker\Shared\Twig\Cache\CacheLoader\FilesystemCacheLoader;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Twig
 * @group Cache
 * @group CacheLoader
 * @group FilesystemCacheLoaderTest
 */
class FilesystemCacheLoaderTest extends PHPUnit_Framework_TestCase
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
        return __DIR__ . '/Fixtures/';
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
