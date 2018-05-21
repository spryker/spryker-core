<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\Model\FileContent;
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface;
use Spryker\Zed\FileManager\FileManagerConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManager
 * @group Business
 * @group Model
 * @group FileContentTest
 * Add your own group annotations below this line
 */
class FileContentTest extends Unit
{
    /**
     * @return \Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface
     */
    protected function getFileSystemServiceMock()
    {
        return $this->getMockBuilder(FileManagerToFileSystemServiceInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\FileManager\FileManagerConfig
     */
    protected function getConfigMock()
    {
        return $this->getMockBuilder(FileManagerConfig::class)->getMock();
    }

    /**
     * @return void
     */
    public function testSave()
    {
        $fileSystemServiceMock = $this->getFileSystemServiceMock();
        $fileSystemServiceMock->expects($this->once())
            ->method('put')
            ->willReturn(null);

        $fileContent = new FileContent(
            $fileSystemServiceMock,
            $this->getConfigMock()
        );

        $fileTransfer = new FileTransfer();
        $fileTransfer->setFileName('report.txt');
        $fileTransfer->setFileContent('this is the report');

        $fileContent->save($fileTransfer);
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $fileSystemServiceMock = $this->getFileSystemServiceMock();
        $fileSystemServiceMock->expects($this->any())
            ->method('delete')
            ->willReturn(null);

        $fileContent = new FileContent(
            $fileSystemServiceMock,
            $this->getConfigMock()
        );

        $fileContent->delete('report.txt');
    }

    /**
     * @return void
     */
    public function testRead()
    {
        $fileSystemServiceMock = $this->getFileSystemServiceMock();
        $fileSystemServiceMock->expects($this->once())
            ->method('read')
            ->willReturn('this is the report');

        $fileContent = new FileContent(
            $fileSystemServiceMock,
            $this->getConfigMock()
        );

        $fileContent = $fileContent->read('report.txt');
        $this->assertEquals('this is the report', $fileContent);
    }
}
