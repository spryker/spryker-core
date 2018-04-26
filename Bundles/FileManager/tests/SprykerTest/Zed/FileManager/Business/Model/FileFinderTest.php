<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business\Model;

use Codeception\Test\Unit;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Spryker\Zed\FileManager\Business\Model\FileLoader;
use Spryker\Zed\FileManager\FileManagerConfig;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group FileManager
 * @group Business
 * @group Model
 * @group FileFinderTest
 * Add your own group annotations below this line
 */
class FileFinderTest extends Unit
{
    /**
     * @return \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface
     */
    protected function getQueryContainer()
    {
        return $this->getMockBuilder(FileManagerQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function getSpyFileQueryMock()
    {
        return $this->getMockBuilder(SpyFileQuery::class)->getMock();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected function getSpyFileInfoQueryMock()
    {
        return $this->getMockBuilder(SpyFileInfoQuery::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\FileManager\FileManagerConfig
     */
    protected function getConfigMock()
    {
        return $this->getMockBuilder(FileManagerConfig::class)->getMock();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    protected function getMockedFile()
    {
        $file = new SpyFile();
        $file->setFileName('test.txt');
        $file->setIdFile(1);

        return $file;
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    protected function getMockedFileInfo()
    {
        $fileInfo = new SpyFileInfo();
        $fileInfo->setExtension('txt');
        $fileInfo->setVersionName('v. 1');
        $fileInfo->setSize(1024);
        $fileInfo->setStorageFileName('report.txt');

        return $fileInfo;
    }

    /**
     * @return void
     */
    public function testGetFile()
    {
        $queryContainerMock = $this->getQueryContainer();
        $spyFileQueryMock = $this->getSpyFileQueryMock();

        $spyFileQueryMock->expects($this->once())
            ->method('findOne')
            ->willReturn($this->getMockedFile());

        $queryContainerMock->expects($this->once())
            ->method('queryFileById')
            ->willReturn($spyFileQueryMock);

        $fileFinder = new FileLoader($queryContainerMock, $this->getConfigMock());
        $file = $fileFinder->getFile(1);
        $this->assertEquals('test.txt', $file->getFileName());
        $this->assertEquals(1, $file->getIdFile());
    }

    /**
     * @return void
     */
    public function testGetLatestFileInfoByFkFile()
    {
        $queryContainerMock = $this->getQueryContainer();
        $spyFileInfoQueryMock = $this->getSpyFileInfoQueryMock();

        $spyFileInfoQueryMock->expects($this->once())
            ->method('findOne')
            ->willReturn($this->getMockedFileInfo());

        $queryContainerMock->expects($this->once())
            ->method('queryFileInfoByIdFile')
            ->willReturn($spyFileInfoQueryMock);

        $fileFinder = new FileLoader($queryContainerMock, $this->getConfigMock());
        $fileInfo = $fileFinder->getLatestFileInfoByFkFile(1);
        $this->assertEquals('txt', $fileInfo->getExtension());
        $this->assertEquals('v. 1', $fileInfo->getVersionName());
        $this->assertEquals(1024, $fileInfo->getSize());
        $this->assertEquals('report.txt', $fileInfo->getStorageFileName());
    }

    /**
     * @return void
     */
    public function testGetFileInfo()
    {
        $queryContainerMock = $this->getQueryContainer();
        $spyFileInfoQueryMock = $this->getSpyFileInfoQueryMock();

        $spyFileInfoQueryMock->expects($this->once())
            ->method('findOne')
            ->willReturn($this->getMockedFileInfo());

        $queryContainerMock->expects($this->once())
            ->method('queryFileInfo')
            ->willReturn($spyFileInfoQueryMock);

        $fileFinder = new FileLoader($queryContainerMock, $this->getConfigMock());
        $fileInfo = $fileFinder->getFileInfo(1);
        $this->assertEquals('txt', $fileInfo->getExtension());
        $this->assertEquals('v. 1', $fileInfo->getVersionName());
        $this->assertEquals(1024, $fileInfo->getSize());
        $this->assertEquals('report.txt', $fileInfo->getStorageFileName());
    }
}
