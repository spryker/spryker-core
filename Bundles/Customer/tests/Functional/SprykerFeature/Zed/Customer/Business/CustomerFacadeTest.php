<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Customer\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerEngine\Zed\Kernel\Locator;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 */
class CustomerFacadeTest extends Test
{

    const TESTER_EMAIL = 'tester@spryker.com';
    const TESTER_PASSWORD = 'tester';
    const TESTER_NAME = 'Tester';
    const TESTER_CITY = 'Testcity';
    const TESTER_ADDRESS1 = 'Testerstreet 23';
    const TESTER_ZIP_CODE = '42';

    /** @var AutoCompletion */
    protected $locator;

    /** @var CustomerFacade */
    protected $customerFacade;

    public function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
        $this->customerFacade = $this->locator->customer()->facade();
    }

    /**
     * @return CustomerTransfer
     */
    protected function createTestCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail(self::TESTER_EMAIL);
        $customerTransfer->setPassword(self::TESTER_PASSWORD);

        return $customerTransfer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return AddressTransfer
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
     * @return CustomerTransfer
     */
    protected function createTestCustomer()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());

        return $customerTransfer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return AddressTransfer
     */
    protected function createTestAddress(CustomerTransfer $customerTransfer)
    {
        $addressTransfer = $this->createTestAddressTransfer($customerTransfer);
        $addressTransfer = $this->locator->customer()->facade()->createAddress($addressTransfer);

        return $addressTransfer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function getTestCustomerTransfer(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->customerFacade->getCustomer($customerTransfer);

        return $customerTransfer;
    }

    public function testGetCustomer()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $this->assertNotNull($customerTransfer->getIdCustomer());
    }

    public function testHasEmailReturnsFalseWithoutCustomer()
    {
        $this->assertFalse($this->customerFacade->hasEmail(self::TESTER_EMAIL));
    }

    public function testHasEmailReturnsTrueWithCustomer()
    {
        $this->createTestCustomer();
        $this->assertTrue($this->customerFacade->hasEmail(self::TESTER_EMAIL));
    }

    public function testRegisterCustomer()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $this->assertNotNull($customerResponseTransfer->getCustomerTransfer()->getRegistrationKey());
    }

    public function testRegisterCustomerWithAlreadyExistingEmail()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());

        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
    }

    public function testConfirmRegistration()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $this->assertNotNull($customerTransfer->getRegistered());
    }

    public function testForgotPassword()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $customerResponseTransfer = $this->customerFacade->forgotPassword($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    public function testRestorePassword()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerResponseTransfer->getCustomerTransfer());
        $this->customerFacade->forgotPassword($customerTransfer);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $customerResponseTransfer = $this->customerFacade->restorePassword($customerTransfer);
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    public function testUpdateCustomer()
    {
        $customerTransfer = $this->createTestCustomer();
        $customerTransfer->setLastName(self::TESTER_NAME);
        $customerResponse = $this->customerFacade->updateCustomer($customerTransfer);
        $this->assertNotNull($customerResponse);
        $this->assertTrue($customerResponse->getIsSuccess());
        $customerTransfer = $customerResponse->getCustomerTransfer();
        $this->assertEquals(self::TESTER_NAME, $customerTransfer->getLastName());
    }

    public function testDeleteCustomer()
    {
        $customerTransfer = $this->createTestCustomer();
        $isSuccess = $this->customerFacade->deleteCustomer($customerTransfer);
        $this->assertTrue($isSuccess);
    }

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

    public function testGetDefaultShippingAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $this->createTestAddress($customerTransfer);
        $addressTransfer = $this->locator->customer()->facade()->getDefaultShippingAddress($customerTransfer);
        $this->assertNotNull($addressTransfer);
    }

    public function testGetDefaultBillingAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $this->createTestAddress($customerTransfer);
        $addressTransfer = $this->locator->customer()->facade()->getDefaultBillingAddress($customerTransfer);
        $this->assertNotNull($addressTransfer);
    }

    public function testRenderAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = $this->createTestAddress($customerTransfer);
        $addressTransfer = $this->locator->customer()->facade()->getAddress($addressTransfer);
        $renderedAddress = $this->locator->customer()->facade()->renderAddress($addressTransfer);
        $this->assertNotNull($renderedAddress);
    }

    public function testDeleteAddress()
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

        $deletedAddress = $this->customerFacade->deleteAddress($addressTransfer);
        $this->assertNotNull($deletedAddress);
    }

}
