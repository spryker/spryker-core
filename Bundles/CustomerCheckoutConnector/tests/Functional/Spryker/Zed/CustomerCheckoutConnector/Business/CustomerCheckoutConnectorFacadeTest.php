<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\CustomerCheckoutConnector\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Zed\CustomerCheckoutConnector\Business\CustomerCheckoutConnectorFacade;

/**
 * @group Zed
 * @group Business
 * @group CustomerCheckoutConnector
 * @group CustomerCheckoutConnectorFacadeTest
 */
class CustomerCheckoutConnectorFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\CustomerCheckoutConnector\Business\CustomerCheckoutConnectorFacade
     */
    protected $customerCheckoutConnectorFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->customerCheckoutConnectorFacade = new CustomerCheckoutConnectorFacade();
    }

    /**
     * @return void
     */
    public function testHydrateOrderSavesExistingCustomerInfoInOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $checkoutRequest = new CheckoutRequestTransfer();

        $customerEntity = new SpyCustomer();
        $customerEntity
            ->setEmail('max@sprykermann.de')
            ->setFirstName('Max')
            ->setLastName('Sprykermann')
            ->setPassword('5pryk3rRul3z')
            ->setCustomerReference('foo');
        $customerEntity->save();

        $checkoutRequest
            ->setIdUser($customerEntity->getIdCustomer())
            ->setBillingAddress(new AddressTransfer())
            ->setShippingAddress(new AddressTransfer());

        $this->customerCheckoutConnectorFacade->hydrateOrderTransfer($orderTransfer, $checkoutRequest);

        $this->assertSame($customerEntity->getIdCustomer(), $orderTransfer->getCustomer()->getIdCustomer());
        $this->assertSame($customerEntity->getEmail(), $orderTransfer->getCustomer()->getEmail());
        $this->assertSame($customerEntity->getFirstName(), $orderTransfer->getCustomer()->getFirstName());
        $this->assertSame($customerEntity->getLastName(), $orderTransfer->getCustomer()->getLastName());
    }

    /**
     * @return void
     */
    public function testHydrateOrderSavesEmailAndGuestInOrder()
    {
        $orderTransfer = new OrderTransfer();
        $checkoutRequest = new CheckoutRequestTransfer();

        $checkoutRequest
            ->setIdUser(null)
            ->setIsGuest(true)
            ->setEmail('max@sprykermann.de')
            ->setBillingAddress(new AddressTransfer())
            ->setShippingAddress(new AddressTransfer());

        $this->customerCheckoutConnectorFacade->hydrateOrderTransfer($orderTransfer, $checkoutRequest);

        $this->assertNotNull($orderTransfer->getCustomer());
        $this->assertSame($checkoutRequest->getEmail(), $orderTransfer->getCustomer()->getEmail());
        $this->assertSame($checkoutRequest->getIsGuest(), $orderTransfer->getCustomer()->getIsGuest());
    }

    /**
     * @return void
     */
    public function testHydrateOrderConvertsAddressesCorrectly()
    {
        $orderTransfer = new OrderTransfer();
        $checkoutRequest = new CheckoutRequestTransfer();

        $billingAddress = new AddressTransfer();
        $billingAddress
            ->setAddress1('A1')
            ->setAddress2('A2')
            ->setAddress3('A3')
            ->setCity('City1')
            ->setIso2Code('DE')
            ->setZipCode('11111');

        $shippingAddress = new AddressTransfer();
        $shippingAddress
            ->setAddress1('B1')
            ->setAddress2('B1')
            ->setAddress3('B1')
            ->setCity('City2')
            ->setIso2Code('DE')
            ->setZipCode('11111');

        $checkoutRequest
            ->setBillingAddress($billingAddress)
            ->setShippingAddress($shippingAddress);

        $this->customerCheckoutConnectorFacade->hydrateOrderTransfer($orderTransfer, $checkoutRequest);

        $this->assertInstanceOf('Generated\\Shared\\Transfer\\AddressTransfer', $orderTransfer->getShippingAddress());
        $this->assertInstanceOf('Generated\\Shared\\Transfer\\AddressTransfer', $orderTransfer->getBillingAddress());

        $this->assertSame($orderTransfer->getBillingAddress()->getAddress1(), $billingAddress->getAddress1());
        $this->assertSame($orderTransfer->getBillingAddress()->getAddress2(), $billingAddress->getAddress2());
        $this->assertSame($orderTransfer->getBillingAddress()->getAddress3(), $billingAddress->getAddress3());
        $this->assertSame($orderTransfer->getBillingAddress()->getCity(), $billingAddress->getCity());
        $this->assertSame($orderTransfer->getBillingAddress()->getIso2Code(), $billingAddress->getIso2Code());
        $this->assertSame($orderTransfer->getBillingAddress()->getZipCode(), $billingAddress->getZipCode());

        $this->assertSame($orderTransfer->getShippingAddress()->getAddress1(), $shippingAddress->getAddress1());
        $this->assertSame($orderTransfer->getShippingAddress()->getAddress2(), $shippingAddress->getAddress2());
        $this->assertSame($orderTransfer->getShippingAddress()->getAddress3(), $shippingAddress->getAddress3());
        $this->assertSame($orderTransfer->getShippingAddress()->getCity(), $shippingAddress->getCity());
        $this->assertSame($orderTransfer->getBillingAddress()->getIso2Code(), $shippingAddress->getIso2Code());
        $this->assertSame($orderTransfer->getShippingAddress()->getZipCode(), $shippingAddress->getZipCode());
    }

}
