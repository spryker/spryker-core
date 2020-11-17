<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerCriteriaTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Customer\Business\Model\PreConditionChecker;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group CustomerFacadeTest
 * Add your own group annotations below this line
 */
class CustomerFacadeTest extends Unit
{
    public const TESTER_EMAIL = 'tester@spryker.com';
    public const TESTER_INVALID_EMAIL = 'tester<>@spryker.com';
    public const TESTER_NON_EXISTING_EMAIL = 'nonexisting@spryker.com';
    public const TESTER_UPDATE_EMAIL = 'update.tester@spryker.com';
    public const TESTER_PASSWORD = '$2tester';
    public const TESTER_NEW_PASSWORD = '$3tester';
    public const TESTER_NAME = 'Tester';

    /**
     * @uses \Spryker\Zed\Customer\Business\Customer\Customer::GLOSSARY_PARAM_VALIDATION_LENGTH
     */
    protected const GLOSSARY_PARAM_VALIDATION_LENGTH = '{{ limit }}';

    /**
     * @uses \Spryker\Zed\Customer\Business\Customer\Customer::GLOSSARY_KEY_MIN_LENGTH_ERROR
     */
    protected const GLOSSARY_KEY_MIN_LENGTH_ERROR = 'customer.password.error.min_length';

    /**
     * @uses \Spryker\Zed\Customer\Business\Customer\Customer::GLOSSARY_KEY_MAX_LENGTH_ERROR
     */
    protected const GLOSSARY_KEY_MAX_LENGTH_ERROR = 'customer.password.error.max_length';

    protected const MIN_LENGTH_CUSTOMER_PASSWORD = 6;
    protected const MAX_LENGTH_CUSTOMER_PASSWORD = 12;

    protected const VALUE_SHORT_PASSWORD = 'p2c';
    protected const VALUE_LONG_PASSWORD = 'p2cfGyY4p2cfGyY4p';

    protected const VALUE_VALID_PASSWORD = 'p2cfGyY4';
    protected const VALUE_NEW_PASSWORD = 'pdcEphDN';

    /**
     * @var \SprykerTest\Zed\Customer\CustomerBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    protected $businessLayerDependencies;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(CustomerDependencyProvider::FACADE_MAIL, $this->getMockBuilder(CustomerToMailInterface::class)->getMock());

        $this->tester->mockConfigMethod('getCustomerReferenceDefaults', new SequenceNumberSettingsTransfer());
        $this->tester->mockConfigMethod('getCustomerPasswordMinLength', static::MIN_LENGTH_CUSTOMER_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordMaxLength', static::MAX_LENGTH_CUSTOMER_PASSWORD);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createTestCustomerTransfer(): CustomerTransfer
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail(self::TESTER_EMAIL);
        $customerTransfer->setPassword(self::TESTER_PASSWORD);

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createTestCustomer(): CustomerTransfer
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getTestCustomerTransfer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $customerTransfer = $this->tester->getFacade()->getCustomer($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @return void
     */
    public function testGetCustomer(): void
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $this->assertNotNull($customerTransfer->getIdCustomer());
    }

    /**
     * @return void
     */
    public function testHasEmailReturnsFalseWithoutCustomer(): void
    {
        $this->assertFalse($this->tester->getFacade()->hasEmail(self::TESTER_EMAIL));
    }

    /**
     * @return void
     */
    public function testHasEmailReturnsTrueWithCustomer(): void
    {
        $this->createTestCustomer();
        $this->assertTrue($this->tester->getFacade()->hasEmail(self::TESTER_EMAIL));
    }

    /**
     * @return void
     */
    public function testRegisterCustomer(): void
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer()->getRegistrationKey());
    }

    /**
     * @return void
     */
    public function testRegisterCustomerWithAlreadyExistingEmail(): void
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());

        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRegisterCustomerFailsWhenInvalidEmailFormatIsProvided(): void
    {
        // Assign
        $this->mockUtilValidateService(false);
        $customerTransfer = $this->createTestCustomerTransfer();

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
    }

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
            $customerResponseTransfer
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
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
            $customerResponseTransfer
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
        ));
    }

    /**
     * @return void
     */
    public function testAddCustomerShouldAddCustomerWhenPasswordHasCorrectLength(): void
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
     * @return void
     */
    public function testRegisterCustomerShouldNotRegisterCustomerWhenPasswordLessThanMinLength(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::PASSWORD => static::VALUE_SHORT_PASSWORD,
        ]))->build();

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
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
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MAX_LENGTH_ERROR,
            $customerResponseTransfer
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
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
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @return void
     */
    public function testUpdateCustomerShouldNotUpdateCustomerWhenPasswordLessThanMinLength(): void
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
            $customerResponseTransfer
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
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
            $customerResponseTransfer
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
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
     * @return void
     */
    public function testUpdateCustomerPasswordShouldNotUpdateCustomerPasswordWhenItLessThanMinLength(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([
            CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD,
        ]);
        $customerTransfer->setPassword(static::VALUE_VALID_PASSWORD)
            ->setNewPassword(static::VALUE_SHORT_PASSWORD);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
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
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MAX_LENGTH_ERROR,
            $customerResponseTransfer
        ));
        $this->assertTrue($this->hasErrorInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MIN_LENGTH_ERROR,
            $customerResponseTransfer
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
        $customerResponseTransfer = $this->tester->getFacade()->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @uses UtilValidateServiceInterface::isEmailFormatValid()
     *
     * @param bool $isEmailFormatValid
     *
     * @return void
     */
    protected function mockUtilValidateService(bool $isEmailFormatValid): void
    {
        $serviceMock = $this->getMockBuilder(CustomerToUtilValidateServiceInterface::class)
            ->setMethods(['isEmailFormatValid'])
            ->getMock();

        $serviceMock
            ->expects($this->any())
            ->method('isEmailFormatValid')
            ->willReturn($isEmailFormatValid);

        $this->tester->setDependency(CustomerDependencyProvider::SERVICE_UTIL_VALIDATE, $serviceMock);
    }

    /**
     * @return void
     */
    public function testRegisterCustomerRegistersCustomerWithValidEmail(): void
    {
        // Assign
        $customerTransfer = $this->createTestCustomerTransfer();
        $this->mockUtilValidateService(true);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testConfirmRegistration(): void
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $this->assertNotNull($customerTransfer->getRegistered());
    }

    /**
     * @return void
     */
    public function testForgotPassword(): void
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $customerResponseTransfer = $this->tester->getFacade()->sendPasswordRestoreMail($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRestorePassword(): void
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->tester->getFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->tester->getFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $this->tester->getFacade()->sendPasswordRestoreMail($customerTransfer);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $customerResponseTransfer = $this->tester->getFacade()->restorePassword($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRestorePasswordNonExistent(): void
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail(self::TESTER_NON_EXISTING_EMAIL);

        $customerResponseTransfer = $this->tester->getFacade()->sendPasswordRestoreMail($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateCustomer(): void
    {
        $customerTransfer = $this->createTestCustomer();
        $customerTransfer->setPassword(null);
        $customerTransfer->setLastName(self::TESTER_NAME);
        $customerResponse = $this->tester->getFacade()->updateCustomer($customerTransfer);
        $this->assertNotNull($customerResponse);
        $this->assertTrue($customerResponse->getIsSuccess());
        $customerTransfer = $customerResponse->getCustomerTransfer();
        $this->assertSame(self::TESTER_NAME, $customerTransfer->getLastName());
    }

    /**
     * @return void
     */
    public function testUpdateCustomerFailsWhenInvalidEmailFormatIsProvided(): void
    {
        // Assign
        $customerTransfer = $this->createTestCustomer();
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
        // Assign
        $customerTransfer = $this->createTestCustomer();
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
        $customerTransfer = $this->createTestCustomer();
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

    /**
     * @return void
     */
    public function testDeleteCustomer(): void
    {
        $customerTransfer = $this->createTestCustomer();
        $isSuccess = $this->tester->getFacade()->deleteCustomer($customerTransfer);
        $this->assertTrue($isSuccess);
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsDoesNotValidateEmailForRegisteredCustomer(): void
    {
        // Assign
        $dummyIdCustomer = 11111;
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIdCustomer($dummyIdCustomer)
                    ->setEmail(static::TESTER_INVALID_EMAIL)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsDoesNotCheckUniqueEmailForRegisteredCustomer(): void
    {
        // Assign
        $dummyCustomerId = 11111;
        $email = 'occupied@spryker.com';
        $this->tester->haveCustomer(['email' => $email, 'password' => static::VALUE_VALID_PASSWORD]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIdCustomer($dummyCustomerId)
                    ->setEmail($email)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsErrorIfEmailIsInvalidForGuest(): void
    {
        // Assign
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIsGuest(true)
                    ->setEmail(static::TESTER_INVALID_EMAIL)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsNoErrorIfEmailIsValidForGuest(): void
    {
        // Assign
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIsGuest(true)
                    ->setEmail(static::TESTER_EMAIL)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsDoesNotCheckUniqueEmailForGuest(): void
    {
        // Assign
        $email = 'occupied@spryker.com';
        $this->tester->haveCustomer(['email' => $email, 'password' => static::VALUE_VALID_PASSWORD]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIsGuest(true)
                    ->setEmail($email)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsErrorIfEmailIsInvalidForNewCustomer(): void
    {
        // Assign
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setEmail(static::TESTER_INVALID_EMAIL)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsErrorIfEmailIsNotUniqueForNewCustomer(): void
    {
        // Assign
        $email = 'occupied@spryker.com';
        $this->tester->haveCustomer(['email' => $email, 'password' => static::VALUE_VALID_PASSWORD]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setEmail($email)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsNoErrorIfEmailIsValidAndUniqueForNewCustomer(): void
    {
        // Assign
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setEmail(static::TESTER_EMAIL)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->tester->getFacade()->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transfer
     * @param bool $hasEmail
     *
     * @return \Spryker\Zed\Customer\Business\CustomerFacade
     */
    private function getFacade(?TransferInterface $transfer = null, bool $hasEmail = true): CustomerFacade
    {
        $customerFacade = new CustomerFacade();
        $customerFacade->setFactory($this->getFactory($transfer, $hasEmail));

        return $customerFacade;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transfer
     * @param bool $hasEmail
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Business\CustomerBusinessFactory
     */
    protected function getFactory(?TransferInterface $transfer = null, bool $hasEmail = true): CustomerBusinessFactory
    {
        $factoryMock = $this->getMockBuilder(CustomerBusinessFactory::class)
            ->getMock();

        if ($transfer instanceof CustomerTransfer || $transfer === null) {
            $factoryMock->method('createCustomer')->willReturn($this->getCustomerMock($transfer, $hasEmail));
        }

        if ($transfer instanceof AddressTransfer) {
            $factoryMock->method('createAddress')->willReturn($this->getAddressMock($transfer));
        }

        return $factoryMock;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     * @param bool $hasEmail
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Business\Customer\Customer
     */
    private function getCustomerMock(?CustomerTransfer $customerTransfer = null, bool $hasEmail = true): Customer
    {
        $customerMock = $this->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $customerMock->method('hasEmail')->willReturn($hasEmail);
        $customerMock->method('register')->willReturn($customerTransfer);
        $customerMock->method('confirmRegistration')->willReturn($customerTransfer);
        $customerMock->method('sendPasswordRestoreMail')->willReturn($customerTransfer);
        $customerMock->method('restorePassword')->willReturn($customerTransfer);
        $customerMock->method('get')->willReturn($customerTransfer);
        $customerMock->method('update')->willReturn($customerTransfer);
        $customerMock->method('updatePassword')->willReturn($customerTransfer);

        return $customerMock;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Business\Customer\Address
     */
    private function getAddressMock(?AddressTransfer $addressTransfer = null): Address
    {
        $addressMock = $this->getMockBuilder(Address::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $addressMock;
    }

    /**
     * @return void
     */
    public function testHasEmail(): void
    {
        $this->assertTrue($this->getFacade()->hasEmail('foo@bar.com'));
    }

    /**
     * @return void
     */
    public function testSendPasswordRestoreMail(): void
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->sendPasswordRestoreMail($customerTransfer));
    }

    /**
     * @return void
     */
    public function testUpdateCustomerPassword(): void
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->updateCustomerPassword($customerTransfer));
    }

    /**
     * @return void
     */
    public function testAnonymizeCustomer(): void
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer(['password' => static::VALUE_VALID_PASSWORD]);

        // Act
        $this->tester->getFacade()->anonymizeCustomer($customerTransfer);

        // Assert
        $this->expectException(CustomerNotFoundException::class);
        $this->tester->getFacade()->getCustomer($customerTransfer);
    }

    /**
     * @return void
     */
    public function testFindCustomerByReference(): void
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer(['password' => static::VALUE_VALID_PASSWORD]);

        // Act
        $customerResponseTransfer = $this->tester->getFacade()->findCustomerByReference($customerTransfer->getCustomerReference());

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertSame($customerTransfer->getCustomerReference(), $customerResponseTransfer->getCustomerTransfer()->getCustomerReference());
    }

    /**
     * @return void
     */
    public function testSendPasswordRestoreMailForCustomerCollectionShouldSetRestorePasswordKey(): void
    {
        // Arrange
        (new SpyCustomer())
            ->setEmail('customer2@shop.com')
            ->setPassword(static::VALUE_VALID_PASSWORD)
            ->setRestorePasswordKey(null)
            ->setCustomerReference('DE--112')
            ->save();

        $customerResponseTransfer = $this->tester->getFacade()->findCustomerByReference('DE--112');

        //Act
        $this->tester->getFacade()->sendPasswordRestoreMailForCustomerCollection(
            (new CustomerCollectionTransfer())->addCustomer($customerResponseTransfer->getCustomerTransfer())
        );

        $customerResponseTransfer = $this->tester->getFacade()->findCustomerByReference('DE--112');
        // Assert
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer()->getRestorePasswordKey());
    }

    /**
     * @dataProvider getCustomerDataProvider
     *
     * @param array $usersData
     * @param \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer $criteriaFilterTransfer
     * @param int $expectedCount
     *
     * @return void
     */
    public function testGetCustomerCollectionByCriteriaShouldReturnCollectionOfCustomers(
        array $usersData,
        CustomerCriteriaFilterTransfer $criteriaFilterTransfer,
        int $expectedCount
    ): void {
        // Arrange
        foreach ($usersData as $item) {
            $this->createCustomerUsingCustomerDataProviderUserData($item);
        }

        // Assert
        $this->assertSame(
            $expectedCount,
            $this->tester->getFacade()->getCustomerCollectionByCriteria($criteriaFilterTransfer)->getCustomers()->count()
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerByCriteriaShouldFindExistingCustomer(): void
    {
        // Arrange
        $customerTransfer = $this->createTestCustomer();
        $customerCriteriaTransfer = (new CustomerCriteriaTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());

        $customerTransferExpanderPlugin = $this
            ->getMockBuilder(CustomerTransferExpanderPluginInterface::class)
            ->getMock();
        $customerTransferExpanderPlugin->expects($this->never())->method('expandTransfer');
        $this->tester->setDependency(
            CustomerDependencyProvider::PLUGINS_CUSTOMER_TRANSFER_EXPANDER,
            [$customerTransferExpanderPlugin]
        );

        // Act
        $customerResponseTransfer = $this->tester->getFacade()
            ->getCustomerByCriteria($customerCriteriaTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess(), 'Customer must be findable by customer reference');
    }

    /**
     * @return void
     */
    public function testGetCustomerByCriteriaShouldFailToFindNonExistingCustomer(): void
    {
        // Arrange
        $customerCriteriaTransfer = (new CustomerCriteriaTransfer())
            ->setCustomerReference('DE--NO-PRESENT');

        // Act
        $customerResponseTransfer = $this->tester->getFacade()
            ->getCustomerByCriteria($customerCriteriaTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess(), 'Non-existing customer must be not findable.');
    }

    /**
     * @return void
     */
    public function testGetCustomerByCriteriaShouldRunExpanders(): void
    {
        // Arrange
        $customerTransfer = $this->createTestCustomer();
        $customerCriteriaTransfer = (new CustomerCriteriaTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setWithExpanders(true);

        $customerTransferExpanderPlugin = $this
            ->getMockBuilder(CustomerTransferExpanderPluginInterface::class)
            ->getMock();
        $customerTransferExpanderPlugin->expects($this->once())->method('expandTransfer');
        $this->tester->setDependency(
            CustomerDependencyProvider::PLUGINS_CUSTOMER_TRANSFER_EXPANDER,
            [$customerTransferExpanderPlugin]
        );

        // Act
        $customerResponseTransfer = $this->tester->getFacade()
            ->getCustomerByCriteria($customerCriteriaTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess(), 'Customer must be findable by customer reference');
    }

    /**
     * @return array
     */
    public function getCustomerDataProvider(): array
    {
        return [
            'get customers with empty password - expects 2' => [
                $this->getUsersData(),
                (new CustomerCriteriaFilterTransfer())->setPasswordExists(false)
                    ->setRestorePasswordKeyExists(true),
                2,
            ],
            'get customers with empty password and empty password restore key - expects 1' => [
                $this->getUsersData(),
                (new CustomerCriteriaFilterTransfer())
                    ->setPasswordExists(false)
                    ->setRestorePasswordKeyExists(false),
                1,
            ],
        ];
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function createCustomerUsingCustomerDataProviderUserData(array $data): void
    {
        $customerEntity = (new SpyCustomer())
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setRestorePasswordKey($data['passwordRestoreKey'])
            ->setCustomerReference($data['customerReference']);

        $customerEntity->save();

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($customerEntity->toArray(), true);

        $this->tester->addCleanup(function () use ($customerTransfer): void {
            $this->tester->getFacade()->deleteCustomer($customerTransfer);
        });
    }

    /**
     * @return array
     */
    protected function getUsersData(): array
    {
        return [
            [
                'email' => 'customer1@shop.com',
                'password' => null,
                'passwordRestoreKey' => null,
                'customerReference' => 'DE--111',
            ],
            [
                'email' => 'customer2@shop.com',
                'password' => null,
                'passwordRestoreKey' => 'fee0292350a14da40ac6f8f9d6cd26ad',
                'customerReference' => 'DE--112',
            ],
            [
                'email' => 'customer3@shop.com',
                'password' => static::VALUE_VALID_PASSWORD,
                'passwordRestoreKey' => 'fee0292350a14da40ac6f8f9d6cd26ad',
                'customerReference' => 'DE--113',
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $errorMessage
     *
     * @return bool
     */
    protected function hasCheckoutErrorMessage(CheckoutResponseTransfer $checkoutResponseTransfer, string $errorMessage): bool
    {
        foreach ($checkoutResponseTransfer->getErrors() as $errorTransfer) {
            if ($errorTransfer->getMessage() === $errorMessage) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param string $errorMessage
     *
     * @return bool
     */
    protected function hasErrorInCustomerResponseTransfer(string $errorMessage, CustomerResponseTransfer $customerResponseTransfer): bool
    {
        $errorTransfers = $customerResponseTransfer->getErrors()->getIterator();

        if (!$errorTransfers->count()) {
            return false;
        }

        return $errorTransfers->current()->getMessage() === $errorMessage;
    }

    /**
     * @param string $message
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return bool
     */
    protected function hasMessageInCustomerResponseTransfer(string $message, CustomerResponseTransfer $customerResponseTransfer): bool
    {
        $messageTransfer = $customerResponseTransfer->getMessage();
        if (!$messageTransfer) {
            return false;
        }

        return $messageTransfer->getValue() === $message;
    }
}
