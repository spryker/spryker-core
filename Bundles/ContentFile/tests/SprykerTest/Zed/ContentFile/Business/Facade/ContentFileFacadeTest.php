<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentFile\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ContentFileListTermTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ContentFile
 * @group Business
 * @group Facade
 * @group Facade
 * @group ContentFileFacadeTest
 * Add your own group annotations below this line
 */
class ContentFileFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ContentFile\ContentFileBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateContentFileValidationSuccessful(): void
    {
        // Arrange
        $contentFileListTermTransfer = (new ContentFileListTermTransfer())
            ->setFileIds(range(1, $this->tester->getModuleConfig()->getMaxFilesInFileList()));

        // Act
        $validationResult = $this->tester->getFacade()->validateContentFileListTerm($contentFileListTermTransfer);

        // Assert
        $this->assertTrue($validationResult->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateContentFileWithOneIdValidationFails(): void
    {
        // Arrange
        $contentFileListTermTransfer = (new ContentFileListTermTransfer())
            ->setFileIds(range(1, $this->tester->getModuleConfig()->getMaxFilesInFileList() + 1));

        // Act
        $validationResult = $this->tester->getFacade()->validateContentFileListTerm($contentFileListTermTransfer);

        // Assert
        $this->assertFalse($validationResult->getIsSuccess());
    }
}
