<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

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
    public function testRestorePassword(): void
    {
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $this->tester->getFacade()->sendPasswordRestoreMail($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->getCustomer($customerTransfer);
        $customerResponseTransfer = $this->tester->getFacade()->restorePassword($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
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
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $this->tester->getFacade()->sendPasswordRestoreMail($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->getCustomer($customerTransfer);
        $customerTransfer->setPassword(static::VALUE_SHORT_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->restorePassword($customerTransfer);

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
