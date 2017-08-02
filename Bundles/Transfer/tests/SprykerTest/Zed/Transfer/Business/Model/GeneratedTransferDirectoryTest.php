<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Transfer\Business\Model\GeneratedTransferDirectory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
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
    public function testClearAbortsOnNonExistingDirectory()
    {
        $fileSystemMock = $this->getFileSystemMock();
        $fileSystemMock
            ->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(false));
        $fileSystemMock
            ->expects($this->never())
            ->method('remove');

        $finderMock = $this->getFinderMock();
        $finderMock
            ->expects($this->never())
            ->method('in');

        $generatedDirectory = new GeneratedTransferDirectory('/foo/bar/baz', $fileSystemMock, $finderMock);
        $generatedDirectory->clear();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Filesystem\Filesystem
     */
    protected function getFileSystemMock()
    {
        return $this
            ->getMockBuilder(Filesystem::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Finder\Finder
     */
    protected function getFinderMock()
    {
        return $this
            ->getMockBuilder(Finder::class)
            ->getMock();
    }

}
