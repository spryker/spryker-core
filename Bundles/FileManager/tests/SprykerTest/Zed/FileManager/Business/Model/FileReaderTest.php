<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business\Model;

use Codeception\Test\Unit;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Spryker\Zed\FileManager\Business\Model\FileContentInterface;
use Spryker\Zed\FileManager\Business\Model\FileLoaderInterface;
use Spryker\Zed\FileManager\Business\Model\FileReader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManager
 * @group Business
 * @group Model
 * @group FileReaderTest
 * Add your own group annotations below this line
 */
class FileReaderTest extends Unit
{
    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileContentInterface
     */
    protected function createFileContentMock()
    {
        return $this->getMockBuilder(FileContentInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface
     */
    protected function createFileFinderMock()
    {
        return $this->getMockBuilder(FileLoaderInterface::class)->getMock();
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
        $fileInfo->setVersion(1);
        $fileInfo->setSize(1024);
        $fileInfo->setStorageFileName('report.txt');
        $fileInfo->setFile($this->getMockedFile());

        return $fileInfo;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerReadResponseTransfer $fileInfo
     *
     * @return void
     */
    protected function assertFileInfo($fileInfo)
    {
        $this->assertEquals('the content of the file', $fileInfo->getContent());
        $this->assertEquals('test.txt', $fileInfo->getFile()->getFileName());
        $this->assertEquals('v. 1', $fileInfo->getFileInfo()->getVersionName());
        $this->assertEquals(1024, $fileInfo->getFileInfo()->getSize());
        $this->assertEquals(1, $fileInfo->getFileInfo()->getVersion());
        $this->assertEquals('txt', $fileInfo->getFileInfo()->getExtension());
        $this->assertEquals('report.txt', $fileInfo->getFileInfo()->getStorageFileName());
    }

    /**
     * @return void
     */
    public function testRead()
    {
        $fileContentMock = $this->createFileContentMock();
        $fileFinderMock = $this->createFileFinderMock();

        $fileFinderMock->expects($this->once())
            ->method('getFileInfo')
            ->willReturn($this->getMockedFileInfo());

        $fileContentMock
            ->method('read')
            ->willReturn('the content of the file');

        $fileReader = new FileReader($fileFinderMock, $fileContentMock);
        $this->assertFileInfo($fileReader->read(1));
    }

    /**
     * @return void
     */
    public function testReadLatestByFileId()
    {
        $fileContentMock = $this->createFileContentMock();
        $fileFinderMock = $this->createFileFinderMock();

        $fileFinderMock->expects($this->once())
            ->method('getLatestFileInfoByFkFile')
            ->willReturn($this->getMockedFileInfo());

        $fileContentMock->expects($this->once())
            ->method('read')
            ->willReturn('the content of the file');

        $fileReader = new FileReader($fileFinderMock, $fileContentMock);
        $this->assertFileInfo($fileReader->readLatestByFileId(1));
    }
}
