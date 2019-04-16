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

/**
 * Auto-generated group annotations
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
    public function testClearCacheEmptiesDirectories()
    {
        $configMock = $this->getConfigMock(['DE']);
        $configMock
            ->expects($this->once())
            ->method('getCachePath')
            ->will($this->returnValue('/path/to/cache'));

        $fileSystemMock = $this->getFileSystemMock();
        $fileSystemMock
            ->expects($this->once())
            ->method('exists')
            ->with($this->equalTo('/path/to/cache'))
            ->will($this->returnValue(true));
        $fileSystemMock
            ->expects($this->once())
            ->method('remove');

        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->once())
            ->method('in')
            ->with($this->equalTo('/path/to/cache'))
            ->will($this->returnSelf());

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearCache();
    }

    /**
     * @return void
     */
    public function testClearAutoLoadCacheEmptiesDirectories()
    {
        $configMock = $this->getConfigMock(['DE']);
        $configMock
            ->expects($this->once())
            ->method('getAutoloaderCachePath')
            ->will($this->returnValue('/path/to/auto-load-cache'));

        $fileSystemMock = $this->getFileSystemMock();
        $fileSystemMock
            ->expects($this->once())
            ->method('exists')
            ->with($this->equalTo('/path/to/auto-load-cache'))
            ->will($this->returnValue(true));
        $fileSystemMock
            ->expects($this->once())
            ->method('remove');

        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->once())
            ->method('in')
            ->with($this->equalTo('/path/to/auto-load-cache'))
            ->will($this->returnSelf());

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearAutoLoaderCache();
    }

    /**
     * @return void
     */
    public function testClearingOfFilesForAllStores()
    {
        $configMock = $this->getConfigMock(['DE', 'EN']);
        $configMock
            ->expects($this->once())
            ->method('getCachePath')
            ->will($this->returnValue('/path/to/{STORE}/cache'));

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

        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->exactly(2))
            ->method('in')
            ->withConsecutive(
                [$this->equalTo('/path/to/DE/cache')],
                [$this->equalTo('/path/to/EN/cache')]
            )
            ->will($this->returnSelf());

        $cacheClearer = new CacheClearer($configMock, $fileSystemMock, $finderMock);
        $cacheClearer->clearCache();
    }

    /**
     * @param string[] $stores
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cache\CacheConfig
     */
    protected function getConfigMock(array $stores)
    {
        $mock = $this
            ->getMockBuilder(CacheConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->once())
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
    protected function getFileSystemMock()
    {
        $mock = $this
            ->getMockBuilder(Filesystem::class)
            ->getMock();

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder
     */
    protected function getFinderMock()
    {
        return $this
            ->getMockBuilder(Finder::class)
            ->getMock();
    }
}
