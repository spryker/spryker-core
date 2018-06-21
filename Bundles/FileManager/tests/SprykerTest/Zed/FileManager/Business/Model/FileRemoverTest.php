<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\Model\FileContentInterface;
use Spryker\Zed\FileManager\Business\Model\FileRemover;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManager
 * @group Business
 * @group Model
 * @group FileRemoverTest
 * Add your own group annotations below this line
 */
class FileRemoverTest extends Unit
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
    protected function createFileRepositoryMock()
    {
        return $this->getMockBuilder(FileManagerRepositoryInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface
     */
    protected function createFileManagerEntityManagerMock()
    {
        return $this->getMockBuilder(FileManagerEntityManagerInterface::class)->getMock();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    protected function getMockedFile()
    {
        $fileTransfer = new FileTransfer();
        $fileTransfer->setFileName('test.txt');
        $fileTransfer->setIdFile(1);

        return $fileTransfer;
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfo
     */
    protected function getMockedFileInfo()
    {
        $fileInfoTransfer = new FileInfoTransfer();
        $fileInfoTransfer->setExtension('txt');
        $fileInfoTransfer->setVersionName('v. 1');
        $fileInfoTransfer->setVersion(1);
        $fileInfoTransfer->setSize(1024);
        $fileInfoTransfer->setStorageFileName('report.txt');

        return $fileInfoTransfer;
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $fileContentMock = $this->createFileContentMock();
        $fileRepositoryMock = $this->createFileRepositoryMock();
        $fileManagerEntityManagerMock = $this->createFileManagerEntityManagerMock();

        $fileRepositoryMock
            ->method('getFileByIdFile')
            ->willReturn($this->getMockedFile());

        $fileContentMock
            ->method('delete')
            ->willReturn(null);

        $fileManagerEntityManagerMock
            ->method('deleteFile')
            ->willReturn(true);

        $fileRemover = new FileRemover(
            $fileRepositoryMock,
            $fileManagerEntityManagerMock,
            $fileContentMock
        );

        $this->assertTrue($fileRemover->delete(1));
    }

    /**
     * @return void
     */
    public function testDeleteFileInfo()
    {
        $fileContentMock = $this->createFileContentMock();
        $fileRepositoryMock = $this->createFileRepositoryMock();
        $fileManagerEntityManagerMock = $this->createFileManagerEntityManagerMock();

        $fileRepositoryMock
            ->method('getFileInfo')
            ->willReturn($this->getMockedFileInfo());

        $fileContentMock
            ->method('delete')
            ->willReturn(null);

        $fileManagerEntityManagerMock
            ->method('deleteFileInfo')
            ->willReturn(true);

        $fileRemover = new FileRemover(
            $fileRepositoryMock,
            $fileManagerEntityManagerMock,
            $fileContentMock
        );

        $this->assertTrue($fileRemover->deleteFileInfo(1));
    }
}
