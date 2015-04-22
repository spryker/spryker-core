<?php

namespace Functional\SprykerFeature\Zed\Customer\Business;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Locator;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Customer\Business\CustomerFacade;
use SprykerFeature\Shared\Customer\Transfer\Customer as CustomerTransfer;
use SprykerFeature\Zed\Customer\Business\Exception\EmailAlreadyRegisteredException;

/**
 * @group Customer
 */
class CustomerFacadeTest extends Test
{
    const TESTER_EMAIL = 'tester@spryker.com';
    const TESTER_PASSWORD = 'tester';
    const TESTER_NAME = 'Tester';
    const TESTER_CITY = 'Testcity';

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
        $customerTransfer = $this->locator->customer()->transferCustomer();
        $customerTransfer->setEmail(self::TESTER_EMAIL);
        $customerTransfer->setPassword(self::TESTER_PASSWORD);

        return $customerTransfer;
    }

    /**
     * @return CustomerTransfer
     */
    protected function createTestCustomer()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerTransfer);

        return $customerTransfer;
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
        $customerTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerTransfer);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $this->assertNotNull($customerTransfer->getIdCustomer());
    }

    public function testRegisterCustomer()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $this->assertNotNull($customerTransfer->getRegistrationKey());
    }

    public function testRegisterCustomerWithAlreadyExistingEmail()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $this->customerFacade->registerCustomer($customerTransfer);
        $exceptionOccurred = false;
        try {
            $this->customerFacade->registerCustomer($customerTransfer);
        } catch (EmailAlreadyRegisteredException $e) {
            $exceptionOccurred = true;
        }
        $this->assertTrue($exceptionOccurred);
    }

    public function testConfirmRegistration()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerTransfer);
        $this->assertNotNull($customerTransfer->getRegistered());
    }

    public function testForgotPassword()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerTransfer);
        $isSuccess = $this->customerFacade->forgotPassword($customerTransfer);
        $this->assertTrue($isSuccess);
    }

    public function testRestorePassword()
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerTransfer = $this->customerFacade->registerCustomer($customerTransfer);
        $customerTransfer = $this->customerFacade->confirmRegistration($customerTransfer);
        $this->customerFacade->forgotPassword($customerTransfer);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $isSuccess = $this->customerFacade->restorePassword($customerTransfer);
        $this->assertTrue($isSuccess);
    }

    public function testUpdateCustomer()
    {
        $customerTransfer = $this->createTestCustomer();
        $customerTransfer->setLastName(self::TESTER_NAME);
        $isSuccess = $this->customerFacade->updateCustomer($customerTransfer);
        $this->assertTrue($isSuccess);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
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
        $addressTransfer = $this->locator->customer()->transferAddress();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setName(self::TESTER_NAME);
        $isSuccess = $this->customerFacade->newAddress($addressTransfer);
        $this->assertTrue($isSuccess);
    }

    public function testUpdateAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = $this->locator->customer()->transferAddress();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setName(self::TESTER_NAME);
        $isSuccess = $this->customerFacade->newAddress($addressTransfer);
        $this->assertTrue($isSuccess);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $addressTransfer = $customerTransfer->getAddresses()->getFirstItem();
        $addressTransfer->setCity(self::TESTER_CITY);
        $addressTransfer = $this->customerFacade->updateAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);
        $this->assertEquals(self::TESTER_CITY, $addressTransfer->getCity());
    }

    public function testSetDefaultShippingAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = $this->locator->customer()->transferAddress();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setName(self::TESTER_NAME);
        $isSuccess = $this->customerFacade->newAddress($addressTransfer);
        $this->assertTrue($isSuccess);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $addressTransfer = $customerTransfer->getAddresses()->getFirstItem();
        $isSuccess = $this->customerFacade->setDefaultShippingAddress($addressTransfer);
        $this->assertTrue($isSuccess);
    }

    public function testSetDefaultBillingAddress()
    {
        $customerTransfer = $this->createTestCustomer();
        $addressTransfer = $this->locator->customer()->transferAddress();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setName(self::TESTER_NAME);
        $isSuccess = $this->customerFacade->newAddress($addressTransfer);
        $this->assertTrue($isSuccess);
        $customerTransfer = $this->getTestCustomerTransfer($customerTransfer);
        $addressTransfer = $customerTransfer->getAddresses()->getFirstItem();
        $isSuccess = $this->customerFacade->setDefaultBillingAddress($addressTransfer);
        $this->assertTrue($isSuccess);
    }
}
