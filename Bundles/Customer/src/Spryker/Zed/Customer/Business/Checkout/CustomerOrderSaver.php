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
use Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface;

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
     * @var \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param \Spryker\Zed\Customer\Business\Customer\CustomerInterface $customer
     * @param \Spryker\Zed\Customer\Business\Customer\AddressInterface $address
     * @param \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerInterface $customer,
        AddressInterface $address,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customer = $customer;
        $this->address = $address;
        $this->customerRepository = $customerRepository;
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
        $this->processCustomerAddress($quoteTransfer->getBillingAddress(), $customer);

        $existingAddresses = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireShipment();
            $addressTransfer = $itemTransfer->getShipment()->getShippingAddress();

            $hash = $this->createAddressTransferHash($addressTransfer);
            if (!isset($existingAddresses[$hash])) {
                $existingAddresses[$hash] = $this ->customerRepository->findAddressByAddressData($addressTransfer);
            }

            $itemTransfer->getShipment()->setShippingAddress($existingAddresses[$hash]);
            $this->processCustomerAddress($existingAddresses[$hash], $customer);
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

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function createAddressTransferHash(AddressTransfer $addressTransfer): string
    {
        return sprintf(
            '%s %s %s %s %s %s %s %s %s %s',
            $addressTransfer->getFkCustomer(),
            $addressTransfer->getFirstName(),
            $addressTransfer->getLastName(),
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getAddress3(),
            $addressTransfer->getZipCode(),
            $addressTransfer->getCity(),
            $addressTransfer->getFkCountry(),
            $addressTransfer->getPhone()
        );
    }
}
