<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Business\Model;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\Business\Customer\Customer;

class CustomerOrderSaver implements CustomerOrderSaverInterface
{

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\Customer
     */
    protected $customer;

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\Address
     */
    protected $address;

    /**
     * @param \Spryker\Zed\Customer\Business\Customer\Customer $customer
     * @param \Spryker\Zed\Customer\Business\Customer\Address $address
     */
    public function __construct(Customer $customer, Address $address)
    {
        $this->customer = $customer;
        $this->address = $address;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $customerTransfer = $quoteTransfer->getCustomer();

        if ($customerTransfer->getIsGuest()) {
            return;
        }

        if ($customerTransfer->getIdCustomer() !== null) {
            $this->customer->update($customerTransfer);
        } else {
            $customerTransfer->setFirstName($quoteTransfer->getBillingAddress()->getFirstName());
            $customerTransfer->setLastName($quoteTransfer->getBillingAddress()->getLastName());
            if (!$customerTransfer->getEmail()) {
                $customerTransfer->setEmail($quoteTransfer->getBillingAddress()->getEmail());
            }
            $customerResponseTransfer = $this->customer->register($customerTransfer);
            $quoteTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());
        }

        $this->persistAddresses($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return void
     */
    protected function persistAddresses(CustomerTransfer $customer)
    {
        foreach ($customer->getBillingAddress() as $billingAddress) {
            $billingAddress->setFkCustomer($customer->getIdCustomer());
            if ($billingAddress->getIdCustomerAddress() === null) {
                $newAddress = $this->address->createAddress($billingAddress);
                $billingAddress->setIdCustomerAddress($newAddress->getIdCustomerAddress());
            } else {
                $this->address->updateAddress($billingAddress);
            }
        }

        foreach ($customer->getShippingAddress() as $shippingAddress) {
            $shippingAddress->setFkCustomer($customer->getIdCustomer());
            if ($shippingAddress->getIdCustomerAddress() === null) {
                $newAddress = $this->address->createAddress($shippingAddress);
                $shippingAddress->setIdCustomerAddress($newAddress->getIdCustomerAddress());
            } else {
                $this->address->updateAddress($shippingAddress);
            }
        }
    }

}
