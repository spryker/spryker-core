<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business\Model;

use Codeception\Test\Unit;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Spryker\Zed\FileManager\Business\Model\FileVersion;
use Spryker\Zed\FileManager\Persistence\FileManagerRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManager
 * @group Business
 * @group Model
 * @group FileVersionTest
 * Add your own group annotations below this line
 */
class FileVersionTest extends Unit
{
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
     * @return \Spryker\Zed\FileManager\Business\Model\FileLoaderInterface
     */
    protected function createFileManagerRepositoryMock()
    {
        return $this->getMockBuilder(FileManagerRepositoryInterface::class)->getMock();
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
     * @return void
     */
    public function testGetNewVersionNumber()
    {
        $fileManagerRepositoryMock = $this->createFileManagerRepositoryMock();

        $fileManagerRepositoryMock->expects($this->once())
            ->method('getLatestFileInfoByIdFile')
            ->willReturn($this->getMockedFileInfo());

        $fileVersion = new FileVersion($fileManagerRepositoryMock);

        $this->assertEquals(2, $fileVersion->getNextVersionNumber(1));
    }

    /**
     * @return void
     */
    public function testGetNewVersionName()
    {
        $fileManagerRepositoryMock = $this->createFileManagerRepositoryMock();
        $fileVersion = new FileVersion($fileManagerRepositoryMock);

        $this->assertEquals('v.2', $fileVersion->getNextVersionName(2));
    }
}
