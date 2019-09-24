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
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Customer\Business\Model\PreConditionChecker;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface;
use Spryker\Zed\Kernel\Container;

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
        $this->customerFacade = new CustomerFacade();
        $this->customerFacade->setFactory($this->getCustomerBusinessFactory());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerBusinessFactory
     */
    protected function getCustomerBusinessFactory(): CustomerBusinessFactory
    {
        $customerBusinessFactory = new CustomerBusinessFactory();
        $customerBusinessFactory->setContainer($this->getContainer());
        $customerBusinessFactory->setConfig($this->getCustomerConfigMock());

        return $customerBusinessFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        $dependencyProvider = new CustomerDependencyProvider();
        $this->businessLayerDependencies = new Container();

        $dependencyProvider->provideBusinessLayerDependencies($this->businessLayerDependencies);

        $this->businessLayerDependencies[CustomerDependencyProvider::FACADE_MAIL] = $this->getMockBuilder(CustomerToMailInterface::class)->getMock();

        return $this->businessLayerDependencies;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createTestCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail(self::TESTER_EMAIL);
        $customerTransfer->setPassword(self::TESTER_PASSWORD);

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createTestCustomer()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getTestCustomerTransfer(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->customerFacade->getCustomer($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @return void
     */
    public function testGetCustomer()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $this->assertNotNull($customerTransfer->getIdCustomer());
    }

    /**
     * @return void
     */
    public function testHasEmailReturnsFalseWithoutCustomer()
    {
        $this->assertFalse($this->customerFacade->hasEmail(self::TESTER_EMAIL));
    }

    /**
     * @return void
     */
    public function testHasEmailReturnsTrueWithCustomer()
    {
        $this->createTestCustomer();
        $this->assertTrue($this->customerFacade->hasEmail(self::TESTER_EMAIL));
    }

    /**
     * @return void
     */
    public function testRegisterCustomer()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer()->getRegistrationKey());
    }

    /**
     * @return void
     */
    public function testRegisterCustomerWithAlreadyExistingEmail()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());

        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRegisterCustomerFailsWhenInvalidEmailFormatIsProvided()
    {
        // Assign
        $this->mockUtilValidateService(false);
        $customerTransfer = $this->createTestCustomerTransfer();

        // Act
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);

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
        $customerResponseTransfer = $this->customerFacade->addCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
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
        $customerResponseTransfer = $this->customerFacade->addCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MAX_LENGTH_ERROR,
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
        $customerResponseTransfer = $this->customerFacade->addCustomer($customerTransfer);

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
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
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
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MAX_LENGTH_ERROR,
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
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);

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
        $customerResponseTransfer = $this->customerFacade->updateCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
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
        $customerResponseTransfer = $this->customerFacade->updateCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MAX_LENGTH_ERROR,
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
        $customerResponseTransfer = $this->customerFacade->updateCustomer($customerTransfer);

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
        $customerResponseTransfer = $this->customerFacade->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
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
        $customerResponseTransfer = $this->customerFacade->updateCustomerPassword($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertTrue($this->hasMessageInCustomerResponseTransfer(
            static::GLOSSARY_KEY_MAX_LENGTH_ERROR,
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
        $customerResponseTransfer = $this->customerFacade->updateCustomerPassword($customerTransfer);

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
    protected function mockUtilValidateService($isEmailFormatValid)
    {
        $serviceMock = $this->getMockBuilder(CustomerToUtilValidateServiceInterface::class)
            ->setMethods(['isEmailFormatValid'])
            ->getMock();

        $serviceMock
            ->expects($this->any())
            ->method('isEmailFormatValid')
            ->willReturn($isEmailFormatValid);

        $this->businessLayerDependencies[CustomerDependencyProvider::SERVICE_UTIL_VALIDATE] = $serviceMock;
    }

    /**
     * @return void
     */
    public function testRegisterCustomerRegistersCustomerWithValidEmail()
    {
        // Assign
        $customerTransfer = $this->createTestCustomerTransfer();
        $this->mockUtilValidateService(true);

        // Act
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testConfirmRegistration()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $this->assertNotNull($customerTransfer->getRegistered());
    }

    /**
     * @return void
     */
    public function testForgotPassword()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $customerResponseTransfer = $this->customerFacade->sendPasswordRestoreMail($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRestorePassword()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $this->customerFacade->sendPasswordRestoreMail($customerTransfer);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $customerResponseTransfer = $this->customerFacade->restorePassword($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRestorePasswordNonExistent()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail(self::TESTER_NON_EXISTING_EMAIL);

        $customerResponseTransfer = $this->customerFacade->sendPasswordRestoreMail($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateCustomer()
    {
        $customerTransfer = $this->createTestCustomer();
        $customerTransfer->setPassword(null);
        $customerTransfer->setLastName(self::TESTER_NAME);
        $customerResponse = $this->customerFacade->updateCustomer($customerTransfer);
        $this->assertNotNull($customerResponse);
        $this->assertTrue($customerResponse->getIsSuccess());
        $customerTransfer = $customerResponse->getCustomerTransfer();
        $this->assertEquals(self::TESTER_NAME, $customerTransfer->getLastName());
    }

    /**
     * @return void
     */
    public function testUpdateCustomerFailsWhenInvalidEmailFormatIsProvided()
    {
        // Assign
        $customerTransfer = $this->createTestCustomer();
        $this->mockUtilValidateService(false);

        // Act
        $customerResponse = $this->customerFacade->updateCustomer($customerTransfer);

        // Assert
        $this->assertFalse($customerResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateCustomerUpdatesValidEmail()
    {
        // Assign
        $customerTransfer = $this->createTestCustomer();
        $customerTransfer->setPassword("other password");
        $this->mockUtilValidateService(true);

        // Act
        $customerResponse = $this->customerFacade->updateCustomer($customerTransfer);

        // Assert
        $this->assertTrue($customerResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateCustomerWithProvidedPasswordShouldSuccessWhenPasswordAreProvided()
    {
        $customerTransfer = $this->createTestCustomer();
        $customerTransfer->setNewPassword('new password');
        $customerTransfer->setPassword(self::TESTER_PASSWORD);
        $customerTransfer->setLastName(self::TESTER_NAME);
        $customerResponse = $this->customerFacade->updateCustomer($customerTransfer);
        $this->assertNotNull($customerResponse);
        $this->assertTrue($customerResponse->getIsSuccess());
        $customerTransfer = $customerResponse->getCustomerTransfer();
        $this->assertEquals(self::TESTER_NAME, $customerTransfer->getLastName());
    }

    /**
     * @return void
     */
    public function testDeleteCustomer()
    {
        $customerTransfer = $this->createTestCustomer();
        $isSuccess = $this->customerFacade->deleteCustomer($customerTransfer);
        $this->assertTrue($isSuccess);
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsDoesNotValidateEmailForRegisteredCustomer()
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
        $this->customerFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsDoesNotCheckUniqueEmailForRegisteredCustomer()
    {
        // Assign
        $dummyCustomerId = 11111;
        $email = 'occupied@spryker.com';
        $this->tester->haveCustomer(['email' => $email]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIdCustomer($dummyCustomerId)
                    ->setEmail($email)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->customerFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsErrorIfEmailIsInvalidForGuest()
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
        $this->customerFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsNoErrorIfEmailIsValidForGuest()
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
        $this->customerFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsDoesNotCheckUniqueEmailForGuest()
    {
        // Assign
        $email = 'occupied@spryker.com';
        $this->tester->haveCustomer(['email' => $email]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIsGuest(true)
                    ->setEmail($email)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->customerFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsErrorIfEmailIsInvalidForNewCustomer()
    {
        // Assign
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setEmail(static::TESTER_INVALID_EMAIL)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->customerFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_INVALID));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsErrorIfEmailIsNotUniqueForNewCustomer()
    {
        // Assign
        $email = 'occupied@spryker.com';
        $this->tester->haveCustomer(['email' => $email]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setEmail($email)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->customerFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($this->hasCheckoutErrorMessage($checkoutResponseTransfer, PreConditionChecker::ERROR_EMAIL_UNIQUE));
    }

    /**
     * @return void
     */
    public function testCheckOrderPreSaveConditionsReturnsNoErrorIfEmailIsValidAndUniqueForNewCustomer()
    {
        // Assign
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setEmail(static::TESTER_EMAIL)
            );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->customerFacade->checkOrderPreSaveConditions($quoteTransfer, $checkoutResponseTransfer);

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
    private function getFacade(?TransferInterface $transfer = null, $hasEmail = true)
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
    protected function getFactory(?TransferInterface $transfer = null, $hasEmail = true)
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
    private function getCustomerMock(?CustomerTransfer $customerTransfer = null, $hasEmail = true)
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
    private function getAddressMock(?AddressTransfer $addressTransfer = null)
    {
        $addressMock = $this->getMockBuilder(Address::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $addressMock;
    }

    /**
     * @return void
     */
    public function testHasEmail()
    {
        $this->assertTrue($this->getFacade()->hasEmail('foo@bar.com'));
    }

    /**
     * @return void
     */
//    public function testRegisterCustomer()
//    {
//        $customerTransfer = new CustomerTransfer();
//        $facade = $this->getFacade($customerTransfer);
//
//        $this->assertSame($customerTransfer, $facade->registerCustomer($customerTransfer));
//    }
//
//    /**
//     * @return void
//     */
//    public function testConfirmRegistration()
//    {
//        $customerTransfer = new CustomerTransfer();
//        $facade = $this->getFacade($customerTransfer);
//
//        $this->assertSame($customerTransfer, $facade->confirmRegistration($customerTransfer));
//    }

    /**
     * @return void
     */
    public function testSendPasswordRestoreMail()
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->sendPasswordRestoreMail($customerTransfer));
    }

    /**
     * @return void
     */
//    public function testRestorePassword()
//    {
//        $customerTransfer = new CustomerTransfer();
//        $facade = $this->getFacade($customerTransfer);
//
//        $this->assertSame($customerTransfer, $facade->restorePassword($customerTransfer));
//    }
//
//    /**
//     * @return void
//     */
//    public function testGetCustomer()
//    {
//        $customerTransfer = new CustomerTransfer();
//        $facade = $this->getFacade($customerTransfer);
//
//        $this->assertSame($customerTransfer, $facade->getCustomer($customerTransfer));
//    }
//
//    /**
//     * @return void
//     */
//    public function testUpdateCustomer()
//    {
//        $customerTransfer = new CustomerTransfer();
//        $facade = $this->getFacade($customerTransfer);
//
//        $this->assertSame($customerTransfer, $facade->updateCustomer($customerTransfer));
//    }

    /**
     * @return void
     */
    public function testUpdateCustomerPassword()
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->updateCustomerPassword($customerTransfer));
    }

    /**
     * @return void
     */
    public function testAnonymizeCustomer()
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer();

        // Act
        $this->customerFacade->anonymizeCustomer($customerTransfer);

        // Assert
        $this->expectException(CustomerNotFoundException::class);
        $this->customerFacade->getCustomer($customerTransfer);
    }

    /**
     * @return void
     */
    public function testFindCustomerByReference()
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer();

        // Act
        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($customerTransfer->getCustomerReference());

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertEquals($customerTransfer->getCustomerReference(), $customerResponseTransfer->getCustomerTransfer()->getCustomerReference());
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $errorMessage
     *
     * @return bool
     */
    protected function hasCheckoutErrorMessage(CheckoutResponseTransfer $checkoutResponseTransfer, $errorMessage)
    {
        foreach ($checkoutResponseTransfer->getErrors() as $errorTransfer) {
            if ($errorTransfer->getMessage() === $errorMessage) {
                return true;
            }
        }

        return false;
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

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\CustomerConfig
     */
    protected function getCustomerConfigMock()
    {
        $customerConfigMock = $this->createMock(CustomerConfig::class);
        $customerConfigMock->method('getCustomerReferenceDefaults')->willReturn(new SequenceNumberSettingsTransfer());
        $customerConfigMock->method('getCustomerPasswordMinLength')->willReturn(static::MIN_LENGTH_CUSTOMER_PASSWORD);
        $customerConfigMock->method('getCustomerPasswordMaxLength')->willReturn(static::MAX_LENGTH_CUSTOMER_PASSWORD);

        return $customerConfigMock;
    }
}
