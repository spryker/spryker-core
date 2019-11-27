<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Transfer\Business\GeneratedFileFinder\GeneratedFileFinderInterface;
use Spryker\Zed\Transfer\Business\Model\GeneratedTransferDirectory;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group GeneratedTransferDirectoryTest
 * Add your own group annotations below this line
 */
class GeneratedTransferDirectoryTest extends Unit
{
    /**
     * @return void
     */
    public function testClearAbortsOnNonExistingDirectory(): void
    {
        $fileSystemMock = $this->getFileSystemMock();
        $fileSystemMock
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(false));
        $fileSystemMock
            ->expects($this->never())
            ->method('remove');

        $finderMock = $this->createGeneratedFileFinderMock();
        $finderMock
            ->expects($this->never())
            ->method('findFiles');

        $generatedDirectory = new GeneratedTransferDirectory('/foo/bar/baz', $fileSystemMock, $finderMock);
        $generatedDirectory->clear();
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\GeneratedFileFinder\GeneratedFileFinderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createGeneratedFileFinderMock(): GeneratedFileFinderInterface
    {
        return $this->createMock(GeneratedFileFinderInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Filesystem\Filesystem
     */
    protected function getFileSystemMock()
    {
        return $this
            ->getMockBuilder(Filesystem::class)
            ->getMock();
    }
}
