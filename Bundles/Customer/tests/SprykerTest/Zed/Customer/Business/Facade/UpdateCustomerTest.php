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
 * @group UpdateCustomerTest
 * Add your own group annotations below this line
 */
class UpdateCustomerTest extends AbstractCustomerFacadeTest
{
    /**
     * @var string
     */
    protected const TESTER_PASSWORD = '$2tester';

    /**
     * @var string
     */
    protected const TESTER_NEW_PASSWORD = '$3tester';

    /**
     * @var string
     */
    protected const TESTER_NAME = 'Tester';

    /**
     * @return void
     */
    public function testUpdateCustomerNotUpdatesCustomerWhenPasswordLessThanMinLength(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_SHORT_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomer($customerTransfer);

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
    public function testUpdateCustomerShouldNotUpdateCustomerWhenPasswordLongerThanMaxLength(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_LONG_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomer($customerTransfer);

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
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordSequenceLimit()
     *
     * @return void
     */
    public function testUpdateCustomerNotUpdatesCustomerWhenPasswordHasTooLongSequence(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_SEQUENCE_TOO_LONG_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordSequenceLimit', static::SEQUENCE_LIMIT_CUSTOMER_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomer($customerTransfer);

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
    public function testUpdateCustomerNotUpdatesCustomerWhenPasswordInDenyList(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_DENY_LIST_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordDenyList', [static::VALUE_DENY_LIST_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomer($customerTransfer);

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
    public function testUpdateCustomerNotUpdatesCustomerWhenPasswordCharacterSetWrong(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_CHARACTER_SET_WRONG_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordCharacterSet', static::CHARACTER_SET_REGEXP);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_CHARACTER_SET,
            $customerResponseTransfer,
        ));
    }

    /**
     * @return void
     */
    public function testUpdateCustomerShouldUpdateCustomerWhenPasswordHasCorrectLength(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_NEW_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordAllowList()
     *
     * @return void
     */
    public function testUpdateCustomerUpdatesCustomerWhenPasswordInAllowList(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_SHORT_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordAllowList', [static::VALUE_SHORT_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordCharacterSet()
     *
     * @return void
     */
    public function testUpdateCustomerPasswordNotUpdatesCustomerPasswordWhenPasswordCharacterSetWrong(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_CHARACTER_SET_WRONG_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordCharacterSet', static::CHARACTER_SET_REGEXP);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_CHARACTER_SET,
            $customerResponseTransfer,
        ));
    }

    /**
     * @return void
     */
    public function testUpdateCustomer(): void
    {
        $customerTransfer = $this->tester->createTestCustomer();
        $customerTransfer->setPassword(null);
        $customerTransfer->setLastName(static::TESTER_NAME);
        $customerResponse = $this->tester->getFacade()->updateCustomer($customerTransfer);
        $this->assertNotNull($customerResponse);
        $this->assertTrue($customerResponse->getIsSuccess());
        $customerTransfer = $customerResponse->getCustomerTransfer();
        $this->assertSame(static::TESTER_NAME, $customerTransfer->getLastName());
    }

    /**
     * @return void
     */
    public function testUpdateCustomerFailsWhenInvalidEmailFormatIsProvided(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $this->mockUtilValidateService(false);

        // Act
        $customerResponse = $this->tester->getFacade()->updateCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateCustomerUpdatesValidEmail(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $customerTransfer->setPassword('other password');
        $this->mockUtilValidateService(true);

        // Act
        $customerResponse = $this->tester->getFacade()->updateCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateCustomerWithProvidedPasswordShouldSuccessWhenPasswordAreProvided(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomer();
        $customerTransfer->setNewPassword(static::TESTER_NEW_PASSWORD);
        $customerTransfer->setPassword(static::TESTER_PASSWORD);
        $customerTransfer->setLastName(static::TESTER_NAME);

        // Act
        $customerResponse = $this->tester->getFacade()->updateCustomer($customerTransfer);
        $customerTransfer = $customerResponse->getCustomerTransfer();

        // Assert
        $this->assertTrue($customerResponse->getIsSuccess(), 'Customer response must be successful.');
        $this->assertSame(static::TESTER_NAME, $customerTransfer->getLastName(), 'Last name was not saved.');
        $this->tester->assertPasswordsEqual($customerTransfer->getPassword(), static::TESTER_NEW_PASSWORD);
    }
}
