<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use DateTime;
use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group RestorePasswordTest
 * Add your own group annotations below this line
 */
class RestorePasswordTest extends AbstractCustomerFacadeTest
{
    /**
     * @var bool
     */
    protected const PASSWORD_VALIDATION_ON_RESTORE_PASSWORD_ENABLED = true;

    /**
     * @return void
     */
    public function testRestoringANonExpiredResetToken(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::RESTORE_PASSWORD_KEY => '7a96da42437d4b12a05bc48ca7c99548',
            CustomerTransfer::RESTORE_PASSWORD_DATE => (new DateTime())->format('Y-m-d H:i:s'),
            CustomerTransfer::PASSWORD => 'changeme123',
        ]);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->restorePassword($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertCount(0, $customerResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testRestoringAnExpiredResetTokenIsEnabled(): void
    {
        $this->tester->mockConfigMethod(
            'isCustomerPasswordResetExpirationEnabled',
            true,
        );

        // Arrange
        $this->tester->mockConfigMethod(
            'getCustomerPasswordResetExpirationPeriod',
            '+2',
        );

        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::RESTORE_PASSWORD_KEY => '51408d2551d148e78612b37d7d3fcbfc',
            CustomerTransfer::RESTORE_PASSWORD_DATE => '2000-10-10 12:00:00',
            CustomerTransfer::PASSWORD => 'changeme123',
        ]);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->restorePassword($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertCount(1, $customerResponseTransfer->getErrors());
        $this->assertEquals(
            'customer.error.confirm_email_link.invalid_or_used',
            $customerResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * This test is the same as before but it disables the expiration check.
     *
     * @return void
     */
    public function testRestoringAnExpiredResetTokenIsDisabled(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'isCustomerPasswordResetExpirationEnabled',
            false,
        );

        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::RESTORE_PASSWORD_KEY => '51408d2551d148e78612b37d7d3fcbfc',
            CustomerTransfer::RESTORE_PASSWORD_DATE => '2000-10-10 12:00:00',
            CustomerTransfer::PASSWORD => 'changeme123',
        ]);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->restorePassword($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertCount(0, $customerResponseTransfer->getErrors());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::isRestorePasswordValidationEnabled()
     *
     * @return void
     */
    public function testRestorePasswordValidatesPasswordWhenPasswordValidationEnabled(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $this->tester->mockConfigMethod(
            'isRestorePasswordValidationEnabled',
            static::PASSWORD_VALIDATION_ON_RESTORE_PASSWORD_ENABLED,
        );
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getCustomerFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $this->tester->getCustomerFacade()->sendPasswordRestoreMail($customerTransfer);
        $customerTransfer = $this->tester->getCustomerFacade()->getCustomer($customerTransfer);
        $customerTransfer->setPassword(static::VALUE_SHORT_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->restorePassword($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer,
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer,
        ));
    }
}
