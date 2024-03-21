<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group UpdateCustomerPasswordTest
 * Add your own group annotations below this line
 */
class UpdateCustomerPasswordTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testUpdateCustomerPasswordNotUpdatesCustomerPasswordWhenItLessThanMinLength(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_SHORT_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->updateCustomerPassword($customerTransfer);

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

    /**
     * @return void
     */
    public function testUpdateCustomerPasswordShouldNotUpdateCustomerPasswordWhenItLongerThanMaxLength(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_LONG_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MAX_LENGTH_ERROR,
            $customerResponseTransfer,
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MAX_LENGTH_ERROR,
            $customerResponseTransfer,
        ));
    }

    /**
     * @return void
     */
    public function testUpdateCustomerPasswordShouldUpdateCustomerPasswordWhenItHasCorrectLength(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_NEW_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordAllowList()
     *
     * @return void
     */
    public function testUpdateCustomerPasswordUpdatesCustomerPasswordWhenPasswordInAllowList(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_SHORT_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordAllowList', [static::VALUE_SHORT_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordSequenceLimit()
     *
     * @return void
     */
    public function testUpdateCustomerPasswordNotUpdatesCustomerPasswordWhenPasswordHasTooLongSequence(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_SEQUENCE_TOO_LONG_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordSequenceLimit', static::SEQUENCE_LIMIT_CUSTOMER_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SEQUENCE,
            $customerResponseTransfer,
        ));
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordDenyList()
     *
     * @return void
     */
    public function testUpdateCustomerPasswordNotUpdatesCustomerPasswordWhenPasswordInDenyList(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_DENY_LIST_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordDenyList', [static::VALUE_DENY_LIST_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_DENY_LIST,
            $customerResponseTransfer,
        ));
    }
}
