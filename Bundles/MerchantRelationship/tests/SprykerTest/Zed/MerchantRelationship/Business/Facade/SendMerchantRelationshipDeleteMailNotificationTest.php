<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationship\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMailFacadeInterface;
use Spryker\Zed\MerchantRelationship\MerchantRelationshipDependencyProvider;
use SprykerTest\Zed\MerchantRelationship\MerchantRelationshipBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationship
 * @group Business
 * @group Facade
 * @group SendMerchantRelationshipDeleteMailNotificationTest
 * Add your own group annotations below this line
 */
class SendMerchantRelationshipDeleteMailNotificationTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_EMAIL_1 = 'test1@email.com';

    /**
     * @var string
     */
    protected const TEST_EMAIL_2 = 'test2@email.com';

    /**
     * @var \SprykerTest\Zed\MerchantRelationship\MerchantRelationshipBusinessTester
     */
    protected MerchantRelationshipBusinessTester $tester;

    /**
     * @return void
     */
    public function testSendsMailWhenBusinessUnitOwnerHaveEmail(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData([], [], [
            CompanyBusinessUnitTransfer::EMAIL => static::TEST_EMAIL_1,
        ]);

        // Assert
        $mailFacadeMock = $this->getMailFacadeMock();
        $mailFacadeMock->expects($this->once())->method('handleMail');
        $this->tester->setDependency(MerchantRelationshipDependencyProvider::FACADE_MAIL, $mailFacadeMock);

        // Act
        $this->tester->getFacade()->sendMerchantRelationshipDeleteMailNotification($merchantRelationshipTransfer);
    }

    /**
     * @return void
     */
    public function testAddsAssigneeCompanyBusinessUnitEmailAsRecipientBcc(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData([], [], [
            CompanyBusinessUnitTransfer::EMAIL => static::TEST_EMAIL_1,
        ], [
            CompanyBusinessUnitTransfer::EMAIL => static::TEST_EMAIL_2,
        ]);

        // Assert
        $mailFacadeMock = $this->getMailFacadeMock();
        $mailFacadeMock->expects($this->once())
            ->method('handleMail')
            ->with($this->callback(function (MailTransfer $mailTransfer) {
                if ($mailTransfer->getRecipientBccs()->count() !== 1) {
                    return false;
                }

                return $mailTransfer->getRecipientBccs()->getIterator()->current()->getEmail() === static::TEST_EMAIL_2;
            }));
        $this->tester->setDependency(MerchantRelationshipDependencyProvider::FACADE_MAIL, $mailFacadeMock);

        // Act
        $this->tester->getFacade()->sendMerchantRelationshipDeleteMailNotification($merchantRelationshipTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNotAddAssigneeCompanyBusinessUnitEmailWhenItDoesNotHaveOne(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData([], [], [
            CompanyBusinessUnitTransfer::EMAIL => static::TEST_EMAIL_1,
        ], [
            CompanyBusinessUnitTransfer::EMAIL => null,
        ]);

        // Assert
        $mailFacadeMock = $this->getMailFacadeMock();
        $mailFacadeMock->expects($this->once())
            ->method('handleMail')
            ->with($this->callback(function (MailTransfer $mailTransfer) {
                return $mailTransfer->getRecipientBccs()->count() === 0;
            }));
        $this->tester->setDependency(MerchantRelationshipDependencyProvider::FACADE_MAIL, $mailFacadeMock);

        // Act
        $this->tester->getFacade()->sendMerchantRelationshipDeleteMailNotification($merchantRelationshipTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNotAddAssigneeCompanyBusinessUnitEmailWhenItIsSameAsOwnerCompanyBusinessUnitEmail(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData([], [], [
            CompanyBusinessUnitTransfer::EMAIL => static::TEST_EMAIL_1,
        ], [
            CompanyBusinessUnitTransfer::EMAIL => static::TEST_EMAIL_1,
        ]);

        // Assert
        $mailFacadeMock = $this->getMailFacadeMock();
        $mailFacadeMock->expects($this->once())
            ->method('handleMail')
            ->with($this->callback(function (MailTransfer $mailTransfer) {
                return $mailTransfer->getRecipientBccs()->count() === 0;
            }));
        $this->tester->setDependency(MerchantRelationshipDependencyProvider::FACADE_MAIL, $mailFacadeMock);

        // Act
        $this->tester->getFacade()->sendMerchantRelationshipDeleteMailNotification($merchantRelationshipTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenOwnerCompanyBusinessUnitDoNotHaveEmail(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData([], [], [
            CompanyBusinessUnitTransfer::EMAIL => null,
        ]);

        // Assert
        $mailFacadeMock = $this->getMailFacadeMock();
        $mailFacadeMock->expects($this->never())->method('handleMail');
        $this->tester->setDependency(MerchantRelationshipDependencyProvider::FACADE_MAIL, $mailFacadeMock);

        // Act
        $this->tester->getFacade()->sendMerchantRelationshipDeleteMailNotification($merchantRelationshipTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNonExistingOwnerCompanyBusinessUnitIdIsProvided(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData();
        $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->setIdCompanyBusinessUnitOrFail(0);

        // Assert
        $mailFacadeMock = $this->getMailFacadeMock();
        $mailFacadeMock->expects($this->never())->method('handleMail');
        $this->tester->setDependency(MerchantRelationshipDependencyProvider::FACADE_MAIL, $mailFacadeMock);

        // Act
        $this->tester->getFacade()->sendMerchantRelationshipDeleteMailNotification($merchantRelationshipTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNonExistingMerchantIdIsProvided(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationshipBySeedData([], [], [
            CompanyBusinessUnitTransfer::EMAIL => null,
        ]);
        $merchantRelationshipTransfer->setFkMerchant(0);

        // Assert
        $mailFacadeMock = $this->getMailFacadeMock();
        $mailFacadeMock->expects($this->never())->method('handleMail');
        $this->tester->setDependency(MerchantRelationshipDependencyProvider::FACADE_MAIL, $mailFacadeMock);

        // Act
        $this->tester->getFacade()->sendMerchantRelationshipDeleteMailNotification($merchantRelationshipTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMailFacadeInterface
     */
    protected function getMailFacadeMock(): MerchantRelationshipToMailFacadeInterface
    {
        return $this->getMockBuilder(MerchantRelationshipToMailFacadeInterface::class)->getMock();
    }
}
