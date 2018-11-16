<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business\File;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\File\FileReader;
use Spryker\Zed\FileManager\Business\FileContent\FileContentInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManager
 * @group Business
 * @group File
 * @group FileReaderTest
 * Add your own group annotations below this line
 */
class FileReaderTest extends Unit
{
    /**
     * @return \Spryker\Zed\FileManager\Business\FileContent\FileContentInterface
     */
    protected function createFileContentMock()
    {
        return $this->getMockBuilder(FileContentInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface
     */
    protected function createFileManagerRepositoryMock()
    {
        return $this->getMockBuilder(FileManagerRepositoryInterface::class)->getMock();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    protected function getMockedFile()
    {
        $fileTransfer = new FileTransfer();
        $fileTransfer->setFileName('test.txt');
        $fileTransfer->setIdFile(1);

        $fileTransfer->addFileInfo($this->getMockedFileInfo());

        return $fileTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function getMockedFileInfo()
    {
        $fileInfoTransfer = new FileInfoTransfer();
        $fileInfoTransfer->setIdFileInfo(1);
        $fileInfoTransfer->setExtension('txt');
        $fileInfoTransfer->setVersionName('v. 1');
        $fileInfoTransfer->setVersion(1);
        $fileInfoTransfer->setSize(1024);
        $fileInfoTransfer->setStorageFileName('report.txt');

        return $fileInfoTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return void
     */
    protected function assertFileInfo(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        $this->assertEquals('the content of the file', $fileManagerDataTransfer->getContent());
        $this->assertEquals('v. 1', $fileManagerDataTransfer->getFileInfo()->getVersionName());
        $this->assertEquals(1024, $fileManagerDataTransfer->getFileInfo()->getSize());
        $this->assertEquals(1, $fileManagerDataTransfer->getFileInfo()->getVersion());
        $this->assertEquals('txt', $fileManagerDataTransfer->getFileInfo()->getExtension());
        $this->assertEquals('report.txt', $fileManagerDataTransfer->getFileInfo()->getStorageFileName());
    }

    /**
     * @return void
     */
    public function testRead()
    {
        $fileContentMock = $this->createFileContentMock();
        $fileManagerRepositoryMock = $this->createFileManagerRepositoryMock();

        $fileManagerRepositoryMock->expects($this->once())
            ->method('getFileByIdFileInfo')
            ->willReturn($this->getMockedFile());

        $fileContentMock
            ->method('read')
            ->willReturn('the content of the file');

        $fileReader = new FileReader($fileManagerRepositoryMock, $fileContentMock);
        $this->assertFileInfo($fileReader->readFileByIdFileInfo(1));
    }

    /**
     * @return void
     */
    public function testReadLatestByFileId()
    {
        $fileContentMock = $this->createFileContentMock();
        $fileManagerRepositoryMock = $this->createFileManagerRepositoryMock();

        $fileManagerRepositoryMock->expects($this->once())
            ->method('getLatestFileInfoByIdFile')
            ->willReturn($this->getMockedFileInfo());

        $fileContentMock->expects($this->once())
            ->method('read')
            ->willReturn('the content of the file');

        $fileReader = new FileReader($fileManagerRepositoryMock, $fileContentMock);
        $this->assertFileInfo($fileReader->readLatestByFileId(1));
    }
}
