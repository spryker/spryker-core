<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Checkout;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Customer\Business\Customer\AddressInterface;
use Spryker\Zed\Customer\Business\Customer\CustomerInterface;

/**
 * @deprecated Use \Spryker\Zed\Customer\Business\Checkout\CustomerOrderSaverWithMultiShippingAddress instead.
 */
class CustomerOrderSaver implements CustomerOrderSaverInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\Customer\CustomerInterface
     */
    protected $customer;

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\AddressInterface
     */
    protected $address;

    /**
     * @param \Spryker\Zed\Customer\Business\Customer\CustomerInterface $customer
     * @param \Spryker\Zed\Customer\Business\Customer\AddressInterface $address
     */
    public function __construct(CustomerInterface $customer, AddressInterface $address)
    {
        $this->customer = $customer;
        $this->address = $address;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderCustomer(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
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

        $this->persistAddresses($quoteTransfer, $customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return void
     */
    protected function persistAddresses(QuoteTransfer $quoteTransfer, CustomerTransfer $customer)
    {
        if ($quoteTransfer->getIsAddressSavingSkipped()) {
            return;
        }

        $quoteTransfer->requireShippingAddress();
        $this->processCustomerAddress($quoteTransfer->getShippingAddress(), $customer);

        if ($quoteTransfer->getBillingSameAsShipping() !== true) {
            $this->processCustomerAddress($quoteTransfer->getBillingAddress(), $customer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function processCustomerAddress(AddressTransfer $addressTransfer, CustomerTransfer $customerTransfer)
    {
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        if (!$addressTransfer->getIdCustomerAddress()) {
            $this->address->createAddressAndUpdateCustomerDefaultAddresses($addressTransfer);
        } else {
            $this->address->updateAddressAndCustomerDefaultAddresses($addressTransfer);
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
