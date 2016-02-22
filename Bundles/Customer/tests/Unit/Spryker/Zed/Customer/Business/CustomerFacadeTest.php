<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Unit\Spryker\Zed\Customer\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;

/**
 * @group Spryker
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 */
class CustomerFacadeTest extends Test
{


    /**
     * @param \Spryker\Shared\Transfer\TransferInterface|null $transfer
     * @param bool $hasEmail
     *
     * @return \Spryker\Zed\Customer\Business\CustomerFacade
     */
    private function getFacade(TransferInterface $transfer = null, $hasEmail = true)
    {
        $facade = new CustomerFacade();
        $facade->setFactory($this->getFactory($transfer, $hasEmail));

        return $facade;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface|null $transfer
     * @param bool $hasEmail
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Customer\Business\CustomerBusinessFactory
     */
    protected function getFactory(TransferInterface $transfer = null, $hasEmail = true)
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
    private function getCustomerMock(CustomerTransfer $customerTransfer = null, $hasEmail = true)
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
    private function getAddressMock(AddressTransfer $addressTransfer = null)
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
    public function testRegisterCustomer()
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->registerCustomer($customerTransfer));
    }

    /**
     * @return void
     */
    public function testConfirmRegistration()
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->confirmRegistration($customerTransfer));
    }

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
    public function testRestorePassword()
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->restorePassword($customerTransfer));
    }

    /**
     * @return void
     */
    public function testGetCustomer()
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->getCustomer($customerTransfer));
    }

    /**
     * @return void
     */
    public function testUpdateCustomer()
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->updateCustomer($customerTransfer));
    }

    /**
     * @return void
     */
    public function testUpdateCustomerPassword()
    {
        $customerTransfer = new CustomerTransfer();
        $facade = $this->getFacade($customerTransfer);

        $this->assertSame($customerTransfer, $facade->updateCustomerPassword($customerTransfer));
    }

}
