<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Setup\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Setup\Business\Model\GeneratedDirectory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Setup
 * @group Business
 * @group Model
 * @group GeneratedDirectoryTest
 * Add your own group annotations below this line
 */
class GeneratedDirectoryTest extends Unit
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

        $generatedDirectory = new GeneratedDirectory('/foo/bar/baz', $fileSystemMock, $finderMock);
        $generatedDirectory->clear();
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

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\Finder
     */
    protected function getFinderMock()
    {
        return $this
            ->getMockBuilder(Finder::class)
            ->getMock();
    }
}
