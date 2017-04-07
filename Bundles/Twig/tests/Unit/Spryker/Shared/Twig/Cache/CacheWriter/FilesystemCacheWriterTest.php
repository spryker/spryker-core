<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Twig\Cache\CacheWriter;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Twig\Cache\CacheWriterInterface;
use Spryker\Shared\Twig\Cache\CacheWriter\FilesystemCacheWriter;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Twig
 * @group Cache
 * @group CacheWriter
 * @group FilesystemCacheWriterTest
 */
class FilesystemCacheWriterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function setUp()
    {
        if (is_file($this->getCacheFile())) {
            unlink($this->getCacheFile());
        }
        if (is_dir($this->getCacheDirectory())) {
            rmdir($this->getCacheDirectory());
        }
    }

    /**
     * @return string
     */
    protected function getCacheFile()
    {
        $cacheDirectory = $this->getCacheDirectory();

        return $cacheDirectory . '/cache.php';
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
    public function testCanBeInstantiatedWIthPathToCacheFile()
    {
        $cacheWriter = new FilesystemCacheWriter($this->getCacheFile());

        $this->assertInstanceOf(CacheWriterInterface::class, $cacheWriter);
    }

    /**
     * @return void
     */
    public function testWriteCreatesDirectoryIfItDoesNotExists()
    {
        $this->assertFalse(is_dir($this->getCacheDirectory()), 'Cache directory exists, make sure you cleanup before test');

        $cacheWriter = new FilesystemCacheWriter($this->getCacheFile());
        $cacheWriter->write(['foo']);

        $this->assertTrue(is_dir($this->getCacheDirectory()), 'Cache directory was not created');
    }

}
