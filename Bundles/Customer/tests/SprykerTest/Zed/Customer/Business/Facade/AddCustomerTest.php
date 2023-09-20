<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group AddCustomerTest
 * Add your own group annotations below this line
 */
class AddCustomerTest extends AbstractCustomerFacadeTest
{
    /**
     * @return void
     */
    public function testAddCustomerShouldNotAddCustomerWhenPasswordLessThanMinLength(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_SHORT_PASSWORD,
        ]))->build();

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->addCustomer($customerTransfer);

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
    public function testAddCustomerShouldNotAddCustomerWhenPasswordLongerThanMaxLength(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_LONG_PASSWORD,
        ]))->build();

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->addCustomer($customerTransfer);

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
    public function testAddCustomerAddsCustomerWhenPasswordIsValid(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]))->build();

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->addCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordAllowList()
     *
     * @return void
     */
    public function testAddCustomerAddsCustomerWhenPasswordInAllowList(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_SHORT_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordAllowList', [static::VALUE_SHORT_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->addCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordSequenceLimit()
     *
     * @return void
     */
    public function testAddCustomerNotAddsCustomerWhenPasswordHasTooLongSequence(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_SEQUENCE_TOO_LONG_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordSequenceLimit', static::SEQUENCE_LIMIT_CUSTOMER_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->addCustomer($customerTransfer);

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
    public function testAddCustomerNotAddsCustomerWhenPasswordInDenyList(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_DENY_LIST_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordDenyList', [static::VALUE_DENY_LIST_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->addCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_DENY_LIST,
            $customerResponseTransfer,
        ));
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordCharacterSet()
     *
     * @return void
     */
    public function testAddCustomerNotAddsCustomerWhenPasswordCharacterSetWrong(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_CHARACTER_SET_WRONG_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordCharacterSet', static::CHARACTER_SET_REGEXP);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->addCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_CHARACTER_SET,
            $customerResponseTransfer,
        ));
    }
}
