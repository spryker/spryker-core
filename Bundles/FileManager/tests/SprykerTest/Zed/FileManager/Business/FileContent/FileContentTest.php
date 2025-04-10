<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business\FileContent;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManager\Business\FileContent\FileContent;
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface;
use Spryker\Zed\FileManager\FileManagerConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManager
 * @group Business
 * @group FileContent
 * @group FileContentTest
 * Add your own group annotations below this line
 */
class FileContentTest extends Unit
{
    /**
     * @return \Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface
     */
    protected function getFileSystemServiceMock(): FileManagerToFileSystemServiceInterface
    {
        return $this->getMockBuilder(FileManagerToFileSystemServiceInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\FileManager\FileManagerConfig
     */
    protected function getConfigMock(): FileManagerConfig
    {
        return $this->getMockBuilder(FileManagerConfig::class)->getMock();
    }

    /**
     * @return void
     */
    public function testSave(): void
    {
        $fileSystemServiceMock = $this->getFileSystemServiceMock();
        $fileSystemServiceMock->expects($this->once())
            ->method('write');

        $fileContent = new FileContent(
            $fileSystemServiceMock,
            $this->getConfigMock(),
        );

        $fileTransfer = new FileTransfer();
        $fileTransfer->setFileName('report.txt');
        $fileTransfer->setFileContent('this is the report');

        $fileContent->save($fileTransfer);
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        $fileSystemServiceMock = $this->getFileSystemServiceMock();
        $fileSystemServiceMock->expects($this->any())
            ->method('delete');

        $fileContent = new FileContent(
            $fileSystemServiceMock,
            $this->getConfigMock(),
        );

        $fileContent->delete('report.txt');
    }

    /**
     * @return void
     */
    public function testRead(): void
    {
        $fileSystemServiceMock = $this->getFileSystemServiceMock();
        $fileSystemServiceMock->expects($this->once())
            ->method('read')
            ->willReturn('this is the report');

        $fileContent = new FileContent(
            $fileSystemServiceMock,
            $this->getConfigMock(),
        );

        $fileContent = $fileContent->read('report.txt');
        $this->assertSame('this is the report', $fileContent);
    }
}
