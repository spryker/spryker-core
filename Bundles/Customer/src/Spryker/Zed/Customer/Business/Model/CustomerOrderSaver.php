<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Business\Model;

use Generated\Shared\Transfer\AddressTransfer;
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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->assertCustomerRequirements($quoteTransfer);

        $customerTransfer = $quoteTransfer->getCustomer();

        if ($customerTransfer->getIsGuest() === true) {
            return;
        }

        if ($this->isNewCustomer($customerTransfer)) {
            $this->createNewCustomer($quoteTransfer, $customerTransfer);
        } else {
            $this->customer->update($customerTransfer);
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
        $this->processCustomerAddressList($customer->getBillingAddress(), $customer);
        $this->processCustomerAddressList($customer->getShippingAddress(), $customer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\AddressTransfer[] $addresses
     *
     * @return void
     */
    protected function processCustomerAddressList(\ArrayObject $addresses, CustomerTransfer $customerTransfer)
    {
        foreach ($addresses as $addressTransfer) {
            $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
            if ($addressTransfer->getIdCustomerAddress() === null) {
                $newAddressTransfer = $this->address->createAddress($addressTransfer);
                $addressTransfer->setIdCustomerAddress($newAddressTransfer->getIdCustomerAddress());
            } else {
                $this->address->updateAddress($addressTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function hydrateCustomerTransfer(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer)
    {
        $customerTransfer->setFirstName($quoteTransfer->getBillingAddress()->getFirstName());
        $customerTransfer->setLastName($quoteTransfer->getBillingAddress()->getLastName());
        if ($customerTransfer->getEmail() === null) {
            $customerTransfer->setEmail($quoteTransfer->getBillingAddress()->getEmail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertCustomerRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireCustomer();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function createNewCustomer(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer)
    {
        $this->hydrateCustomerTransfer($quoteTransfer, $customerTransfer);
        $customerResponseTransfer = $this->customer->register($customerTransfer);
        $quoteTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isNewCustomer(CustomerTransfer $customerTransfer)
    {
        return $customerTransfer->getIdCustomer() === null;
    }

}
