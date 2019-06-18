<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Checkout;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
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
        $this->existingAddresses = [];

        $quoteTransfer = $this->persistBillingAddress($quoteTransfer, $customer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->persistShippingAddress($itemTransfer, $customer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function persistBillingAddress(QuoteTransfer $quoteTransfer, CustomerTransfer $customer): QuoteTransfer
    {
        $quoteTransfer->requireBillingAddress();
        $billingAddressTransfer = $quoteTransfer->getBillingAddress();

        if ($billingAddressTransfer->getIsAddressSavingSkipped()) {
            return $quoteTransfer;
        }

        $billingAddressTransfer = $this->processNewUniqueCustomerAddress($billingAddressTransfer, $customer);
        $quoteTransfer->setBillingAddress($billingAddressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return void
     */
    protected function persistShippingAddress(ItemTransfer $itemTransfer, CustomerTransfer $customer): void
    {
        $itemTransfer->requireShipment();
        $itemTransfer->getShipment()
            ->requireShippingAddress();
        $shippingAddressTransfer = $itemTransfer->getShipment()
            ->getShippingAddress();

        if ($shippingAddressTransfer->getIsAddressSavingSkipped()) {
            return;
        }

        $shippingAddressTransfer = $this->processNewUniqueCustomerAddress($shippingAddressTransfer, $customer);
        $itemTransfer->getShipment()
            ->setShippingAddress($shippingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function processNewUniqueCustomerAddress(AddressTransfer $addressTransfer, CustomerTransfer $customer): AddressTransfer
    {
        if ($addressTransfer->getFkCustomer() === null) {
            $addressTransfer->setFkCustomer($customer->getIdCustomer());
        }

        if ($this->isAddressNewAndUnique($addressTransfer)) {
            return $addressTransfer;
        }

        $this->processCustomerAddress($addressTransfer, $customer);

        $this->setAddressIsAlreadyPersisted($addressTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    protected function isAddressNewAndUnique(AddressTransfer $addressTransfer): bool
    {
        return $addressTransfer->getIdCompanyUnitAddress() !== null
            || $addressTransfer->getIdCustomerAddress() !== null
            || $this->isAddressAlreadyPersisted($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    protected function isAddressAlreadyPersisted(AddressTransfer $addressTransfer): bool
    {
        $key = $this->customerService->getUniqueAddressKey($addressTransfer);
        if (isset($this->existingAddresses[$key])) {
            return true;
        }

        $customerAddressTransfer = $this->customerRepository->findAddressByAddressData($addressTransfer);
        if ($customerAddressTransfer === null) {
            return false;
        }

        $this->existingAddresses[$key] = true;

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return void
     */
    protected function setAddressIsAlreadyPersisted(AddressTransfer $addressTransfer): void
    {
        $key = $this->customerService->getUniqueAddressKey($addressTransfer);
        $this->existingAddresses[$key] = true;
    }
}
