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
     * @var \Spryker\Zed\ContentFile\Business\ContentFileFacadeInterface
     */
    protected $contentFileFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->contentFileFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testValidateContentFileValidationSuccessful(): void
    {
        // Arrange
        $contentFileListTermTransfer = (new ContentFileListTermTransfer())
            ->setFileIds(range(0, 15));

        // Act
        $validationResult = $this->contentFileFacade->validateContentFileListTerm($contentFileListTermTransfer);

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
            ->setFileIds(range(0, 21));

        // Act
        $validationResult = $this->contentFileFacade->validateContentFileListTerm($contentFileListTermTransfer);

        // Assert
        $this->assertFalse($validationResult->getIsSuccess());
    }
}
