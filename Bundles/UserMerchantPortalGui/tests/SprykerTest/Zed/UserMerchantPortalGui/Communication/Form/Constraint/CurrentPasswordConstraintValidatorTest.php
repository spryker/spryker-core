<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\CurrentPasswordConstraint;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\CurrentPasswordConstraintValidator;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group CurrentPasswordConstraintValidatorTest
 * Add your own group annotations below this line
 */
class CurrentPasswordConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @return \Symfony\Component\Validator\ConstraintValidator
     */
    protected function createValidator(): ConstraintValidator
    {
        return new CurrentPasswordConstraintValidator();
    }

    /**
     * @return void
     */
    public function testReturnsSuccessForCorrectPassword(): void
    {
        // Arrange
        $currentPasswordConstraint = $this->createCurrentPasswordConstraint(
            $this->createMerchantUserFacadeMock(true)
        );

        // Act
        $this->validator->validate('myS3cr3tP4ssw0rD', $currentPasswordConstraint);

        // Assert
        $this->assertNoViolation();
    }

    /**
     * @return void
     */
    public function testReturnsErrorForExistingUserEmail(): void
    {
        // Arrange
        $currentPasswordConstraint = $this->createCurrentPasswordConstraint(
            $this->createMerchantUserFacadeMock(false)
        );

        // Act
        $this->validator->validate('myS3cr3tP4ssw0rD', $currentPasswordConstraint);

        // Assert
        $this->buildViolation($currentPasswordConstraint->getMessage())
            ->assertRaised();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     *
     * @return \Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\CurrentPasswordConstraint
     */
    protected function createCurrentPasswordConstraint(
        MockObject $merchantUserFacade
    ): CurrentPasswordConstraint {
        return new CurrentPasswordConstraint([
            CurrentPasswordConstraint::OPTION_MERCHANT_USER_FACADE => $merchantUserFacade,
        ]);
    }

    /**
     * @param bool $willPasswordBeValid
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected function createMerchantUserFacadeMock(
        bool $willPasswordBeValid
    ): UserMerchantPortalGuiToMerchantUserFacadeInterface {
        $merchantUserFacade = $this->getMockBuilder(UserMerchantPortalGuiToMerchantUserFacadeInterface::class)
            ->getMock();

        $user = (new UserTransfer())
            ->setPassword('uS6ahmishuveexe8aiG0tuukeingaxuu2siex2quaiYeeph8Raetah0gei0xa9fo');

        $merchantUserFacade
            ->method('getCurrentMerchantUser')
            ->willReturn(
                (new MerchantUserTransfer())->setUser($user)
            );

        $merchantUserFacade
            ->method('isValidPassword')
            ->willReturn($willPasswordBeValid);

        return $merchantUserFacade;
    }
}
