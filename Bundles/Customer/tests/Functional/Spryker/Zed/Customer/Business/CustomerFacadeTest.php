<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Customer\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Kernel\Container;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Customer
 * @group Business
 * @group CustomerFacadeTest
 */
class CustomerFacadeTest extends Test
{

    const TESTER_EMAIL = 'tester@spryker.com';
    const TESTER_NON_EXISTING_EMAIL = 'nonexisting@spryker.com';
    const TESTER_PASSWORD = 'tester';
    const TESTER_NAME = 'Tester';
    const TESTER_CITY = 'Testcity';
    const TESTER_ADDRESS1 = 'Testerstreet 23';
    const TESTER_ZIP_CODE = '42';

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacade
     */
    protected $customerFacade;

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
        $container = new Container();

        $dependencyProvider->provideBusinessLayerDependencies($container);

        $container[CustomerDependencyProvider::FACADE_MAIL] = $this->getMockBuilder(CustomerToMailInterface::class)->getMock();

        return $container;
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

}
