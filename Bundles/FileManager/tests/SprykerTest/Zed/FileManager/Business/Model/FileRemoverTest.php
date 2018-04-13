<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business\Model;

use Codeception\Test\Unit;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Propel\Runtime\Propel;
use Spryker\Zed\FileManager\Business\Model\FileContentInterface;
use Spryker\Zed\FileManager\Business\Model\FileLoaderInterface;
use Spryker\Zed\FileManager\Business\Model\FileRemover;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;

/**
 * Auto-generated group annotations
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
    protected function createFileFinderMock()
    {
        return $this->getMockBuilder(FileLoaderInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer
     */
    protected function createFileManagerQueryContainerMock()
    {
        return $this->getMockBuilder(FileManagerQueryContainer::class)->getMock();
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
        $fileInfo->setFileExtension('txt');
        $fileInfo->setVersionName('v. 1');
        $fileInfo->setVersion(1);
        $fileInfo->setSize(1024);
        $fileInfo->setStorageFileName('report.txt');
        $fileInfo->setFile($this->getMockedFile());

        return $fileInfo;
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $fileContentMock = $this->createFileContentMock();
        $fileFinderMock = $this->createFileFinderMock();
        $fileManagerQueryContainerMock = $this->createFileManagerQueryContainerMock();

        $fileFinderMock
            ->method('getFile')
            ->willReturn($this->getMockedFile());

        $fileContentMock
            ->method('delete')
            ->willReturn(null);

        $fileManagerQueryContainerMock
            ->method('getConnection')
            ->willReturn(Propel::getConnection());

        $fileRemover = new FileRemover(
            $fileFinderMock,
            $fileContentMock,
            $fileManagerQueryContainerMock
        );

        $this->assertTrue($fileRemover->delete(1));
    }

    /**
     * @return void
     */
    public function testDeleteFileInfo()
    {
        $fileContentMock = $this->createFileContentMock();
        $fileFinderMock = $this->createFileFinderMock();
        $fileManagerQueryContainerMock = $this->createFileManagerQueryContainerMock();

        $fileFinderMock
            ->method('getFileInfo')
            ->willReturn($this->getMockedFileInfo());

        $fileContentMock
            ->method('delete')
            ->willReturn(null);

        $fileManagerQueryContainerMock
            ->method('getConnection')
            ->willReturn(Propel::getConnection());

        $fileRemover = new FileRemover(
            $fileFinderMock,
            $fileContentMock,
            $fileManagerQueryContainerMock
        );

        $this->assertTrue($fileRemover->deleteFileInfo(1));
    }
}
