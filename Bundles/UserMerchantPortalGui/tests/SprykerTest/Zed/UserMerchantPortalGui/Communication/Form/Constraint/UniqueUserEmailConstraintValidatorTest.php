<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security;

use Generated\Shared\Transfer\UserTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\UniqueUserEmailConstraint;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\UniqueUserEmailConstraintValidator;
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
 * @group UniqueUserEmailConstraintValidatorTest
 * Add your own group annotations below this line
 */
class UniqueUserEmailConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @return \Symfony\Component\Validator\ConstraintValidator
     */
    protected function createValidator(): ConstraintValidator
    {
        return new UniqueUserEmailConstraintValidator();
    }

    /**
     * @return void
     */
    public function testReturnsSuccessForNonExistingUserEmail(): void
    {
        // Arrange
        $uniqueUserEmailConstraint = $this->createUniqueUserEmailConstraint(
            $this->createMerchantUserFacadeMock(null)
        );

        // Act
        $this->validator->validate('someone@spryker.com', $uniqueUserEmailConstraint);

        // Assert
        $this->assertNoViolation();
    }

    /**
     * @return void
     */
    public function testReturnsErrorForExistingUserEmail(): void
    {
        // Arrange
        $uniqueUserEmailConstraint = $this->createUniqueUserEmailConstraint(
            $this->createMerchantUserFacadeMock(new UserTransfer())
        );

        // Act
        $this->validator->validate('someone@spryker.com', $uniqueUserEmailConstraint);

        // Assert
        $this->buildViolation($uniqueUserEmailConstraint->getMessage())
            ->assertRaised();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     *
     * @return \Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint\UniqueUserEmailConstraint
     */
    protected function createUniqueUserEmailConstraint(
        MockObject $merchantUserFacade
    ): UniqueUserEmailConstraint {
        return new UniqueUserEmailConstraint([
            UniqueUserEmailConstraint::OPTION_MERCHANT_USER_FACADE => $merchantUserFacade,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer|null $returnForFindUser
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected function createMerchantUserFacadeMock(
        ?UserTransfer $returnForFindUser
    ): UserMerchantPortalGuiToMerchantUserFacadeInterface {
        $merchantUserFacade = $this->getMockBuilder(UserMerchantPortalGuiToMerchantUserFacadeInterface::class)
            ->getMock();

        $merchantUserFacade
            ->method('findUser')
            ->willReturn($returnForFindUser);

        return $merchantUserFacade;
    }
}
