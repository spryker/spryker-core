<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group SendPasswordRestoreMailTest
 * Add your own group annotations below this line
 */
class SendPasswordRestoreMailTest extends AbstractCustomerFacadeTest
{
    /**
     * @var string
     */
    protected const TESTER_NON_EXISTING_EMAIL = 'nonexisting@spryker.com';

    /**
     * @return void
     */
    public function testForgotPassword(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getCustomerFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->sendPasswordRestoreMail($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRestorePasswordNonExistent(): void
    {
        // Arrange
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail(static::TESTER_NON_EXISTING_EMAIL);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->sendPasswordRestoreMail($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSendPasswordRestoreMailAddsLocaleToRestoreLink(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $localeName = 'de_DE';
        $customerTransfer->setLocale((new LocaleTransfer())->setLocaleName($localeName));

        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $customerResponseTransfer->getCustomerTransfer();
        $this->tester->getCustomerFacade()->confirmRegistration($customerTransfer);

        // Act
        $this->tester->getCustomerFacade()->sendPasswordRestoreMail($customerTransfer);

        // Assert
        $this->assertStringContainsString(
            '_locale=' . $localeName,
            $customerTransfer->getRestorePasswordLink(),
            'Restore password link should contain the correct locale parameter',
        );
    }
}
