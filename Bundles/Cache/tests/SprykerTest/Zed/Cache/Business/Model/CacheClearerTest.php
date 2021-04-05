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
    public function testClearCodeBucketCacheEmptiesDirectories(): void
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig $configMock
         */
        $configMock = $this->getConfigMock([]);
        $configMock
            ->expects($this->once())
            ->method('getCodeBucketCachePath')
            ->will($this->returnValue(
                sprintf('/path/to/cache/code-bucket-%s', 'DE')
            ));

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Filesystem\Filesystem $fileSystemMock
         */
        $fileSystemMock = $this->getFileSystemMock();
        $fileSystemMock
            ->expects($this->once())
            ->method('remove');

        /**
         * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\SplFileInfo
         */
        $splFileInfoMock = $this->createMock(SplFileInfo::class);
        $splFileInfoMock
            ->method('getPath')
            ->willReturn('/path/to/cache');

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder $finderMock
         */
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->once())
            ->method('directories')
            ->will($this->returnSelf());
        $finderMock
            ->expects($this->exactly(2))
            ->method('depth')
            ->will($this->returnSelf());
        $finderMock
            ->expects($this->exactly(2))
            ->method('in')
            ->with($this->equalTo('/path/to/cache'))
            ->will($this->returnSelf());
        $finderMock
            ->expects($this->once())
            ->method('name')
            ->with($this->equalTo('code-bucket-DE'))
            ->will($this->returnSelf());
        $finderMock
            ->method('getIterator')
            ->willReturnCallback(function () use ($splFileInfoMock) {
                yield $splFileInfoMock;
            });

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearCodeBucketCache();
    }

    /**
     * @return void
     */
    public function testClearDefaultCodeBucketCacheEmptiesDirectories(): void
    {
        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig $configMock
         */
        $configMock = $this->getConfigMock([]);
        $configMock
            ->expects($this->once())
            ->method('getDefaultCodeBucketCachePath')
            ->will($this->returnValue('/path/to/cache/code-bucket'));

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Filesystem\Filesystem $fileSystemMock
         */
        $fileSystemMock = $this->getFileSystemMock();
        $fileSystemMock
            ->expects($this->once())
            ->method('remove');

        /**
         * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\SplFileInfo
         */
        $splFileInfoMock = $this->createMock(SplFileInfo::class);
        $splFileInfoMock
            ->method('getPath')
            ->willReturn('/path/to/cache');

        /**
         * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder $finderMock
         */
        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->once())
            ->method('directories')
            ->will($this->returnSelf());
        $finderMock
            ->expects($this->exactly(2))
            ->method('depth')
            ->will($this->returnSelf());
        $finderMock
            ->expects($this->exactly(2))
            ->method('in')
            ->with($this->equalTo('/path/to/cache'))
            ->will($this->returnSelf());
        $finderMock
            ->expects($this->once())
            ->method('name')
            ->with($this->equalTo('code-bucket'))
            ->will($this->returnSelf());
        $finderMock
            ->method('getIterator')
            ->willReturnCallback(function () use ($splFileInfoMock) {
                yield $splFileInfoMock;
            });

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearDefaultCodeBucketCache();
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
        $mock = $this
            ->getMockBuilder(Filesystem::class)
            ->getMock();

        return $mock;
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
}
