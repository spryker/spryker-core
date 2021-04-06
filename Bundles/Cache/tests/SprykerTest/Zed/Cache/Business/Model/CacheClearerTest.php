<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cache\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Cache\Business\Model\CacheClearer;
use Spryker\Zed\Cache\CacheConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cache
 * @group Business
 * @group Model
 * @group CacheClearerTest
 * Add your own group annotations below this line
 */
class CacheClearerTest extends Unit
{
    public const TEST_DIRECTORY_NAME = 'path/to/cache/code-bucket-de/';
    public const TEST_DEFAULT_DIRECTORY_NAME = 'path/to/cache/code-bucket/';

    /**
     * @return void
     */
    public function testClearCacheEmptiesDirectories(): void
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig $configMock
         */
        $configMock = $this->getConfigMock(['DE']);
        $configMock
            ->expects($this->once())
            ->method('getCachePath')
            ->will($this->returnValue('/path/to/cache'));

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Filesystem\Filesystem $fileSystemMock
         */
        $fileSystemMock = $this->getFileSystemMock();
        $fileSystemMock
            ->expects($this->once())
            ->method('exists')
            ->with($this->equalTo('/path/to/cache'))
            ->will($this->returnValue(true));
        $fileSystemMock
            ->expects($this->once())
            ->method('remove');

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder $finderMock
         */
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->once())
            ->method('in')
            ->with($this->equalTo('/path/to/cache'))
            ->will($this->returnSelf());

        $finderMock
            ->expects($this->once())
            ->method('depth')
            ->will($this->returnSelf());

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearCache();
    }

    /**
     * @return void
     */
    public function testClearAutoLoadCacheEmptiesDirectories(): void
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig $configMock
         */
        $configMock = $this->getConfigMock(['DE']);
        $configMock
            ->expects($this->once())
            ->method('getAutoloaderCachePath')
            ->will($this->returnValue('/path/to/auto-load-cache'));

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Filesystem\Filesystem $fileSystemMock
         */
        $fileSystemMock = $this->getFileSystemMock();
        $fileSystemMock
            ->expects($this->once())
            ->method('exists')
            ->with($this->equalTo('/path/to/auto-load-cache'))
            ->will($this->returnValue(true));
        $fileSystemMock
            ->expects($this->once())
            ->method('remove');

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder $finderMock
         */
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->once())
            ->method('in')
            ->with($this->equalTo('/path/to/auto-load-cache'))
            ->will($this->returnSelf());

        $finderMock
            ->expects($this->once())
            ->method('depth')
            ->will($this->returnSelf());

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearAutoLoaderCache();
    }

    /**
     * @return void
     */
    public function testClearingOfFilesForAllStores(): void
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig $configMock
         */
        $configMock = $this->getConfigMock(['DE', 'EN']);
        $configMock
            ->expects($this->once())
            ->method('getCachePath')
            ->will($this->returnValue('/path/to/{STORE}/cache'));

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Filesystem\Filesystem $fileSystemMock
         */
        $fileSystemMock = $this->getFileSystemMock();
        $fileSystemMock
            ->expects($this->exactly(2))
            ->method('exists')
            ->withConsecutive(
                [$this->equalTo('/path/to/DE/cache')],
                [$this->equalTo('/path/to/EN/cache')]
            )
            ->will($this->returnValue(true));
        $fileSystemMock
            ->expects($this->exactly(2))
            ->method('remove');

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder $finderMock
         */
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->exactly(2))
            ->method('in')
            ->withConsecutive(
                [$this->equalTo('/path/to/DE/cache')],
                [$this->equalTo('/path/to/EN/cache')]
            )
            ->will($this->returnSelf());

        $finderMock
            ->expects($this->exactly(2))
            ->method('depth')
            ->will($this->returnSelf());

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearCache();
    }

    /**
     * @return void
     */
    public function testExecuteShouldDeleteCodeBucketDirectory(): void
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig $configMock
         */
        $configMock = $this->getCodeBucketConfigMock('getCodeBucketCachePath', $this->getTestCodeBucketDirectory());

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder $finderMock
         */
        $finderMock = $this->getCodeBucketFinderMock($this->getTestCodeBucketDirectory());

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Filesystem\Filesystem $fileSystemMock
         */
        $fileSystemMock = new Filesystem();

        $this->assertTrue(is_dir($this->getTestCodeBucketDirectory()));

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearCodeBucketCache();

        $this->assertFalse(is_dir($this->getTestCodeBucketDirectory()));
    }

    /**
     * @return void
     */
    public function testExecuteShouldDeleteDefaultCodeBucketDirectory(): void
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig $configMock
         */
        $configMock = $this->getCodeBucketConfigMock('getDefaultCodeBucketCachePath', $this->getTestDefaultCodeBucketDirectory());

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder $finderMock
         */
        $finderMock = $this->getCodeBucketFinderMock($this->getTestDefaultCodeBucketDirectory());

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Filesystem\Filesystem $fileSystemMock
         */
        $fileSystemMock = new Filesystem();

        $this->assertTrue(is_dir($this->getTestDefaultCodeBucketDirectory()));

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearDefaultCodeBucketCache();

        $this->assertFalse(is_dir($this->getTestDefaultCodeBucketDirectory()));
    }

    /**
     * @param string[] $stores
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig
     */
    protected function getConfigMock(array $stores): CacheConfig
    {
        $mock = $this
            ->getMockBuilder(CacheConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getAllowedStores')
            ->will($this->returnValue($stores));

        $mock
            ->expects($this->any())
            ->method('getStorePatternMarker')
            ->will($this->returnValue(CacheConfig::STORE_PATTERN_MARKER));

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Filesystem\Filesystem
     */
    protected function getFileSystemMock(): Filesystem
    {
        return $this
            ->getMockBuilder(Filesystem::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder
     */
    protected function getFinderMock(): Finder
    {
        return $this
            ->getMockBuilder(Finder::class)
            ->getMock();
    }

    /**
     * @param string $method
     * @param string $directory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig
     */
    protected function getCodeBucketConfigMock(string $method, string $directory): CacheConfig
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig $configMock
         */
        $configMock = $this->getConfigMock([]);
        $configMock
            ->expects($this->once())
            ->method($method)
            ->will($this->returnValue($directory));

        return $configMock;
    }

    /**
     * @param string $directory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder
     */
    protected function getCodeBucketFinderMock(string $directory): Finder
    {
        $splFileInfoMock = new SplFileInfo($directory, $directory, $directory);

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder $finderMock
         */
        $finderMock = $this->createMock(Finder::class);
        $finderMock
            ->expects($this->exactly(2))
            ->method('depth')
            ->will($this->returnSelf());
        $finderMock
            ->expects($this->exactly(2))
            ->method('in')
            ->with($this->equalTo(dirname($directory)))
            ->will($this->returnSelf());
        $finderMock
            ->expects($this->once())
            ->method('directories')
            ->will($this->returnSelf());
        $finderMock
            ->expects($this->once())
            ->method('name')
            ->with($this->equalTo(basename($directory)))
            ->will($this->returnSelf());
        $finderMock
            ->method('getIterator')
            ->willReturnCallback(function () use ($splFileInfoMock) {
                yield $splFileInfoMock;
            });

        return $finderMock;
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        $testCodeBucketDirectory = $this->getTestCodeBucketDirectory();
        if (!is_dir($testCodeBucketDirectory)) {
            mkdir($testCodeBucketDirectory, 0775, true);
        }

        $testDefaultCodeBucketDirectory = $this->getTestDefaultCodeBucketDirectory();
        if (!is_dir($testDefaultCodeBucketDirectory)) {
            mkdir($testDefaultCodeBucketDirectory, 0775, true);
        }
    }

    /**
     * @return string
     */
    private function getTestCodeBucketDirectory(): string
    {
        return codecept_data_dir(static::TEST_DIRECTORY_NAME);
    }

    /**
     * @return string
     */
    private function getTestDefaultCodeBucketDirectory(): string
    {
        return codecept_data_dir(static::TEST_DEFAULT_DIRECTORY_NAME);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        if (is_dir($this->getTestCodeBucketDirectory())) {
            rmdir($this->getTestCodeBucketDirectory());
        }

        if (is_dir($this->getTestDefaultCodeBucketDirectory())) {
            rmdir($this->getTestDefaultCodeBucketDirectory());
        }
    }
}
