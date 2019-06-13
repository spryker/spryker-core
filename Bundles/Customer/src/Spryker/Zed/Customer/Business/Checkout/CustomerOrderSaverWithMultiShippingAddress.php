<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Checkout;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Customer\CustomerServiceInterface;
use Spryker\Zed\Customer\Business\Customer\AddressInterface;
use Spryker\Zed\Customer\Business\Customer\CustomerInterface;
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
     * Keys are unique strings generated using address data. Values don't matter.
     *
     * @var bool[]
     */
    protected $existingAddresses = [];

    /**
     * @var \Spryker\Service\Customer\CustomerServiceInterface
     */
    protected $customerService;

    /**
     * @param \Spryker\Zed\Customer\Business\Customer\CustomerInterface $customer
     * @param \Spryker\Zed\Customer\Business\Customer\AddressInterface $address
     * @param \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface $customerRepository
     * @param \Spryker\Service\Customer\CustomerServiceInterface $customerService
     */
    public function __construct(
        CustomerInterface $customer,
        AddressInterface $address,
        CustomerRepositoryInterface $customerRepository,
        CustomerServiceInterface $customerService
    ) {
        parent::__construct($customer, $address);

        $this->customerRepository = $customerRepository;
        $this->customerService = $customerService;
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

        $quoteTransfer->requireBillingAddress();
        $billingAddress = $this->processNewUniqueCustomerAddress($quoteTransfer->getBillingAddress(), $customer);
        $quoteTransfer->setBillingAddress($billingAddress);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIsAddressSavingSkipped()) {
                continue;
            }

            $itemTransfer->requireShipment();
            $shipmentTransfer = $itemTransfer->getShipment();

            $shipmentTransfer->requireShippingAddress();
            $addressTransfer = $shipmentTransfer->getShippingAddress();

            $addressTransfer = $this->processNewUniqueCustomerAddress($addressTransfer, $customer);

            $itemTransfer->getShipment()->setShippingAddress($addressTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function processNewUniqueCustomerAddress(AddressTransfer $addressTransfer, CustomerTransfer $customer): AddressTransfer
    {
        if ($this->isAddressForSave($addressTransfer)) {
            return $addressTransfer;
        }

        if ($addressTransfer->getFkCustomer() === null) {
            $addressTransfer->setFkCustomer($customer->getIdCustomer());
        }

        $this->processCustomerAddress($addressTransfer, $customer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    protected function checkNewAddressIsAlreadyPersist(AddressTransfer $addressTransfer): bool
    {
        $key = $this->customerService->getUniqueAddressKey($addressTransfer);
        if (isset($this->existingAddresses[$key])) {
            return true;
        }

        $customerAddressTransfer = $this->customerRepository->findAddressByAddressData($addressTransfer);
        $this->existingAddresses[$key] = true;

        return $customerAddressTransfer !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    protected function isAddressForSave(AddressTransfer $addressTransfer): bool
    {
        return $addressTransfer->getIdCompanyUnitAddress() !== null
            || $addressTransfer->getIdCustomerAddress() !== null
            || $this->checkNewAddressIsAlreadyPersist($addressTransfer);
    }
}
