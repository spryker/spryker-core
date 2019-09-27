<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig\Cache\CacheWriter;

use Codeception\Test\Unit;
use Spryker\Shared\Twig\Cache\CacheWriter\FilesystemCacheWriter;
use Spryker\Shared\Twig\Cache\CacheWriterInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group Cache
 * @group CacheWriter
 * @group FilesystemCacheWriterTest
 * Add your own group annotations below this line
 */
class FilesystemCacheWriterTest extends Unit
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
    public function testCanBeInstantiatedWIthPathToCacheFile()
    {
        $cacheWriter = new FilesystemCacheWriter($this->getCacheFile(), 0777);

        $this->assertInstanceOf(CacheWriterInterface::class, $cacheWriter);
    }

    /**
     * @return void
     */
    public function testWriteCreatesDirectoryIfItDoesNotExists()
    {
        $this->assertFalse(is_dir($this->getCacheDirectory()), 'Cache directory exists, make sure you cleanup before test');

        $cacheWriter = new FilesystemCacheWriter($this->getCacheFile(), 0777);
        $cacheWriter->write(['foo']);

        $this->assertTrue(is_dir($this->getCacheDirectory()), 'Cache directory was not created');
    }
}
