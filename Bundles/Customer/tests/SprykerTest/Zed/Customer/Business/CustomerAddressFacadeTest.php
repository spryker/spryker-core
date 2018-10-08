<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group CustomerAddressFacadeTest
 * Add your own group annotations below this line
 */
class CustomerAddressFacadeTest extends Unit
{
    protected const TESTER_EMAIL = 'tester@spryker.com';
    protected const TESTER_PASSWORD = '$2tester';
    protected const TESTER_NAME = 'Tester';
    protected const TESTER_CITY = 'Testcity';
    protected const TESTER_ADDRESS1 = 'Testerstreet 23';
    protected const TESTER_ZIP_CODE = '42';
    protected const TESTER_FK_COUNTRY_GERMANY = '60';

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
     * @return void
     */
    public function testGetAddressesHasCountry()
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer();
        $this->customerFacade->createAddressAndUpdateCustomerDefaultAddresses($customerTransfer->getShippingAddress()[0]);

        // Act
        $addressesTransfer = $this->customerFacade->getAddresses($customerTransfer);
        $addressTransfer = $addressesTransfer->getAddresses()[0];

        // Assert
        $this->assertEquals(self::TESTER_FK_COUNTRY_GERMANY, $addressTransfer->getCountry()->getIdCountry());
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
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getTestCustomerTransfer(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->customerFacade->getCustomer($customerTransfer);

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
}
