<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Customer\Business\Model\PreConditionChecker;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
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
    const TESTER_EMAIL = 'tester@spryker.com';
    const TESTER_INVALID_EMAIL = 'tester<>@spryker.com';
    const TESTER_NON_EXISTING_EMAIL = 'nonexisting@spryker.com';
    const TESTER_UPDATE_EMAIL = 'update.tester@spryker.com';
    const TESTER_PASSWORD = 'tester';
    const TESTER_NAME = 'Tester';
    const TESTER_CITY = 'Testcity';
    const TESTER_ADDRESS1 = 'Testerstreet 23';
    const TESTER_ZIP_CODE = '42';

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
    public function setUp()
    {
        parent::setUp();
        $this->customerFacade = new CustomerFacade();
        $this->customerFacade->setFactory($this->getBusinessFactory());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerBusinessFactory
     */
    protected function getBusinessFactory()
    {
        $customerBusinessFactory = new CustomerBusinessFactory();
        $customerBusinessFactory->setContainer($this->getContainer());

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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createTestAddressTransfer(CustomerTransfer $customerTransfer)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $addressTransfer->setEmail(self::TESTER_EMAIL);
        $addressTransfer->setFirstName(self::TESTER_NAME);
        $addressTransfer->setLastName(self::TESTER_NAME);
        $addressTransfer->setAddress1(self::TESTER_ADDRESS1);
        $addressTransfer->setCity(self::TESTER_CITY);
        $addressTransfer->setZipCode(self::TESTER_ZIP_CODE);

        return $addressTransfer;
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
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createTestAddress(CustomerTransfer $customerTransfer)
    {
        $addressTransfer = $this->createTestAddressTransfer($customerTransfer);
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);

        return $addressTransfer;
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
    public function testNewAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setFirstName(self::TESTER_NAME);
        $addressTransfer->setLastName(self::TESTER_NAME);
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setFirstName(self::TESTER_NAME);
        $addressTransfer->setLastName(self::TESTER_NAME);
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);

        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        $addressTransfer->setCity(self::TESTER_CITY);
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $addressTransfer = $this->customerFacade->updateAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);
        $this->assertEquals(self::TESTER_CITY, $addressTransfer->getCity());
    }

    /**
     * @return void
     */
    public function testSetDefaultShippingAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setFirstName(self::TESTER_NAME);
        $addressTransfer->setLastName(self::TESTER_NAME);
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        $isSuccess = $this->customerFacade->setDefaultShippingAddress($addressTransfer);
        $this->assertTrue($isSuccess);
    }

    /**
     * @return void
     */
    public function testSetDefaultBillingAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setFirstName(self::TESTER_NAME);
        $addressTransfer->setLastName(self::TESTER_NAME);
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        $isSuccess = $this->customerFacade->setDefaultBillingAddress($addressTransfer);
        $this->assertTrue($isSuccess);
    }

    /**
     * @return void
     */
    public function testGetDefaultShippingAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $this->createTestAddress($customerTransfer);
        $addressTransfer = $this->customerFacade->getDefaultShippingAddress($customerTransfer);
        $this->assertNotNull($addressTransfer);
    }

    /**
     * @return void
     */
    public function testGetDefaultBillingAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $this->createTestAddress($customerTransfer);
        $addressTransfer = $this->customerFacade->getDefaultBillingAddress($customerTransfer);
        $this->assertNotNull($addressTransfer);
    }

    /**
     * @return void
     */
    public function testRenderAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = $this->createTestAddress($customerTransfer);
        $addressTransfer = $this->customerFacade->getAddress($addressTransfer);
        $renderedAddress = $this->customerFacade->renderAddress($addressTransfer);
        $this->assertNotNull($renderedAddress);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerWithAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setFirstName(self::TESTER_NAME);
        $addressTransfer->setLastName(self::TESTER_NAME);
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);

        return $this->getTestCustomerTransfer($customerTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAddress()
    {
        $customerTransfer = $this->createCustomerWithAddress();

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        $deletedAddress = $this->customerFacade->deleteAddress($addressTransfer);
        $this->assertNotNull($deletedAddress);
    }

    /**
     * @return void
     */
    public function testDeleteDefaultAddress()
    {
        $customerTransfer = $this->createCustomerWithAddress();

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        $this->customerFacade->setDefaultBillingAddress($addressTransfer);

        $deletedAddress = $this->customerFacade->deleteAddress($addressTransfer);
        $this->assertNotNull($deletedAddress);

        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $this->assertNull($customerTransfer->getDefaultBillingAddress());
    }

    /**
     * @expectedException \Spryker\Zed\Customer\Business\Exception\AddressNotFoundException
     *
     * @return void
     */
    public function testDeleteCustomerWithDefaultAddresses()
    {
        $customerTransfer = $this->createCustomerWithAddress();

        $addresses = $customerTransfer->getAddresses()->getAddresses();
        $addressTransfer = $addresses[0];

        $this->customerFacade->setDefaultBillingAddress($addressTransfer);
        $this->customerFacade->setDefaultShippingAddress($addressTransfer);

        $isSuccess = $this->customerFacade->deleteCustomer($customerTransfer);
        $this->assertTrue($isSuccess);

        $this->customerFacade->getAddress($addressTransfer);
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Customer\Business\CustomerBusinessFactory
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Customer\Business\Customer\Customer
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Customer\Business\Customer\Address
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
        $customerTransfer = $this->createTestCustomer();

        // Act
        $this->customerFacade->anonymizeCustomer($customerTransfer);

        // Assert
        $this->expectException(CustomerNotFoundException::class);
        $this->customerFacade->getCustomer($customerTransfer);
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
}
