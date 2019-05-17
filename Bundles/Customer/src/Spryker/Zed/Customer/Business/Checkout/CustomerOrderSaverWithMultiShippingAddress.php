<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Checkout;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Customer\Business\Customer\AddressInterface;
use Spryker\Zed\Customer\Business\Customer\CustomerInterface;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerBusinessFactory getFactory()
 */
class CustomerOrderSaverWithMultiShippingAddress extends CustomerOrderSaver
{
    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Generated\Shared\Transfer\AddressTransfer[]
     */
    protected $existingAddresses = [];

    /**
     * @var \Spryker\Zed\Customer\CustomerConfig
     */
    protected $customerConfig;

    /**
     * @param \Spryker\Zed\Customer\Business\Customer\CustomerInterface $customer
     * @param \Spryker\Zed\Customer\Business\Customer\AddressInterface $address
     * @param \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface $customerRepository
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     */
    public function __construct(
        CustomerInterface $customer,
        AddressInterface $address,
        CustomerRepositoryInterface $customerRepository,
        CustomerConfig $customerConfig
    ) {
        parent::__construct($customer, $address);

        $this->customerRepository = $customerRepository;
        $this->customerConfig = $customerConfig;
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

        $this->existingAddresses = [];

        $billingAddressTransfer = $quoteTransfer->getBillingAddress();
        $this->processCustomerAddress(
            $this->getCustomerAddress($billingAddressTransfer, $customer),
            $customer
        );

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIsAddressSavingSkipped() === true) {
                return;
            }

            $itemTransfer->requireShipment();

            $addressTransfer = $itemTransfer->getShipment()->getShippingAddress();
            $addressTransfer = $this->getCustomerAddress($addressTransfer, $customer);

            $itemTransfer->getShipment()->setShippingAddress($addressTransfer);

            $this->processCustomerAddress($addressTransfer, $customer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getCustomerAddress(AddressTransfer $addressTransfer, CustomerTransfer $customer): AddressTransfer
    {
        if ($addressTransfer->getFkCustomer() === null) {
            $addressTransfer->setFkCustomer($customer->getIdCustomer());
        }

        $key = $this->getAddressTransferKey($addressTransfer);
        if (!isset($this->existingAddresses[$key])) {
            $customerAddressTransfer = $this->customerRepository->findAddressByAddressData($addressTransfer);
            $this->existingAddresses[$key] = $customerAddressTransfer ?: $addressTransfer;
        }

        return $this->existingAddresses[$key];
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getAddressTransferKey(AddressTransfer $addressTransfer): string
    {
        $addressData = $addressTransfer->toArray(true, true);

        foreach ($this->customerConfig->getAddressExcludedFields() as $addressExcludedField) {
            unset($addressData[$addressExcludedField]);
        }

        return implode($addressData);
    }
}
