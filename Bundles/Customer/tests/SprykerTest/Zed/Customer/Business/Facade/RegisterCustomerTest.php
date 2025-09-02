<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\PostCustomerRegistrationPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group RegisterCustomerTest
 * Add your own group annotations below this line
 */
class RegisterCustomerTest extends AbstractCustomerFacadeTest
{
    /**
     * @var string
     */
    protected const VALUE_HAS_SEQUENCE_VALID_PASSWORD = '4sxjjvrt';

    /**
     * @return void
     */
    public function testRegisterCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer()->getRegistrationKey());
    }

    /**
     * @return void
     */
    public function testExecutesPostCustomerRegistrationPlugins(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();

        $postCustomerRegistrationPluginMock = $this->getMockBuilder(PostCustomerRegistrationPluginInterface::class)->getMock();
        $this->tester->setDependency(CustomerDependencyProvider::PLUGINS_POST_CUSTOMER_REGISTRATION, [$postCustomerRegistrationPluginMock]);

        // Assert
        $postCustomerRegistrationPluginMock
            ->expects($this->once())
            ->method('execute');

        // Act
        $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);
    }

    /**
     * @return void
     */
    public function testRegisterCustomerWithAlreadyExistingEmail(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $customerTransfer = $this->tester->createTestCustomerTransfer();

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRegisterCustomerFailsWhenInvalidEmailFormatIsProvided(): void
    {
        // Arrange
        $this->mockUtilValidateService(false);
        $customerTransfer = $this->tester->createTestCustomerTransfer();

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRegisterCustomerRegistersCustomerWithValidEmail(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $this->mockUtilValidateService(true);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRegisterCustomerNotRegistersCustomerWhenPasswordLessThanMinLength(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_SHORT_PASSWORD,
        ]))->build();

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

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
    public function testRegisterCustomerShouldNotRegisterCustomerWhenPasswordLongerThanMaxLength(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_LONG_PASSWORD,
        ]))->build();

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

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
    public function testRegisterCustomerShouldRegisterCustomerWhenPasswordHasCorrectLength(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]))->build();

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordAllowList()
     *
     * @return void
     */
    public function testRegisterCustomerRegistersCustomerWhenPasswordInAllowList(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_SHORT_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordAllowList', [static::VALUE_SHORT_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordSequenceLimit()
     *
     * @return void
     */
    public function testRegisterCustomerNotRegistersCustomerWhenPasswordHasTooLongSequence(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_SEQUENCE_TOO_LONG_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordSequenceLimit', static::SEQUENCE_LIMIT_CUSTOMER_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

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
    public function testRegisterCustomerNotRegistersCustomerWhenPasswordInDenyList(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_DENY_LIST_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordDenyList', [static::VALUE_DENY_LIST_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

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
    public function testRegisterCustomerNotRegistersCustomerWhenPasswordCharacterSetWrong(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_CHARACTER_SET_WRONG_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordCharacterSet', static::CHARACTER_SET_REGEXP);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_CHARACTER_SET,
            $customerResponseTransfer,
        ));
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordSequenceLimit()
     *
     * @return void
     */
    public function testRegisterCustomerRegistersCustomerWhenPasswordHasValidLengthSequence(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_HAS_SEQUENCE_VALID_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordSequenceLimit', static::SEQUENCE_LIMIT_CUSTOMER_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordDenyList()
     *
     * @return void
     */
    public function testRegisterCustomerRegistersCustomerWhenPasswordNotInDenyList(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordDenyList', [static::VALUE_DENY_LIST_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::getCustomerPasswordCharacterSet()
     *
     * @return void
     */
    public function testRegisterCustomerRegistersCustomerWhenPasswordCharacterSetValid(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]))->build();
        $this->tester->mockConfigMethod('getCustomerPasswordCharacterSet', static::CHARACTER_SET_REGEXP);

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @return void
     */
    public function testRegisterCustomerAddsConfirmationLinkWithLocale(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();

        $localeName = 'de_DE';

        $customerTransfer->setLocale((new LocaleTransfer())->setLocaleName($localeName));

        // Act
        $customerResponseTransfer = $this->tester->getCustomerFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertStringContainsString(
            '_locale=' . $localeName,
            $customerResponseTransfer->getCustomerTransfer()->getConfirmationLink(),
        );
    }
}
