<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentNavigation\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentNavigationTermTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ContentNavigation
 * @group Business
 * @group Facade
 * @group ContentNavigationFacadeTest
 * Add your own group annotations below this line
 */
class ContentNavigationFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ContentNavigation\ContentNavigationBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateContentNavigationTermWillReturnErrorMessageIfNavigationDoesNotExists(): void
    {
        //Arrange
        $navigationKey = 'not-existing-navigation-key';
        $contentNavigationTermTransfer = $this->createContentNavigationTermTransfer($navigationKey);

        //Act
        $contentValidationResponseTransfer = $this->tester->getFacade()->validateContentNavigationTerm($contentNavigationTermTransfer);

        //Assert
        $this->assertFalse($contentValidationResponseTransfer->getIsSuccess(), 'Validation result does not match expected value.');
        $this->assertGreaterThanOrEqual(
            1,
            $contentValidationResponseTransfer->getParameterMessages()->count(),
            'Validation messages count does not match expected value.'
        );
    }

    /**
     * @return void
     */
    public function testValidateContentNavigationTermWillReturnSuccessfulResponseIfNavigationExists(): void
    {
        //Arrange
        $navigationTransfer = $this->tester->haveNavigation();
        $contentNavigationTermTransfer = $this->createContentNavigationTermTransfer($navigationTransfer->getKey());

        //Act
        $contentValidationResponseTransfer = $this->tester->getFacade()->validateContentNavigationTerm($contentNavigationTermTransfer);

        //Assert
        $this->assertTrue($contentValidationResponseTransfer->getIsSuccess(), 'Validation result does not match expected value.');
        $this->assertCount(
            0,
            $contentValidationResponseTransfer->getParameterMessages(),
            'Validation messages count does not match expected value.'
        );
    }

    /**
     * @param string $navigationKey
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTermTransfer
     */
    protected function createContentNavigationTermTransfer(string $navigationKey): ContentNavigationTermTransfer
    {
        return (new ContentNavigationTermTransfer())->setNavigationKey($navigationKey);
    }
}
