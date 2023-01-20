<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FileCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use SprykerTest\Zed\FileManager\FileManagerBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManager
 * @group Business
 * @group Facade
 * @group GetFileCollectionTest
 * Add your own group annotations below this line
 */
class GetFileCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\FileManager\FileManagerBusinessTester
     */
    protected FileManagerBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetFileCollectionReturnsCollectionWithFiveFilesWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        $this->tester->insertFilesCollection(15);
        $fileCriteriaTransfer = (new FileCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(5)->setOffset(10),
            );

        // Act
        $fileCollectionTransfer = $this->tester->getFacade()
            ->getFileCollection($fileCriteriaTransfer);

        // Assert
        $this->assertCount(5, $fileCollectionTransfer->getFiles());
    }
}
