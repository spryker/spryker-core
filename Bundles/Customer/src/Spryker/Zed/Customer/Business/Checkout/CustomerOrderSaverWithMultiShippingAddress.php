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

/**
 * @method \Spryker\Zed\Customer\Business\CustomerBusinessFactory getFactory()
 */
class CustomerOrderSaverWithMultiShippingAddress extends CustomerOrderSaver
{
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
     * @param \Spryker\Service\Customer\CustomerServiceInterface $customerService
     */
    public function __construct(
        CustomerInterface $customer,
        AddressInterface $address,
        CustomerServiceInterface $customerService
    ) {
        parent::__construct($customer, $address);

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
        $billingAddressTransfer = $quoteTransfer->requireBillingAddress()->getBillingAddress();

        if ($billingAddressTransfer === null || $billingAddressTransfer->getIsAddressSavingSkipped()) {
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
        $shipmentTransfer = $itemTransfer->requireShipment()->getShipment();
        $shippingAddressTransfer = $shipmentTransfer->requireShippingAddress()->getShippingAddress();

        if ($shippingAddressTransfer->getIsAddressSavingSkipped()) {
            return;
        }

        $shippingAddressTransfer = $this->processNewUniqueCustomerAddress($shippingAddressTransfer, $customer);
        $shipmentTransfer->setShippingAddress($shippingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function processNewUniqueCustomerAddress(
        AddressTransfer $addressTransfer,
        CustomerTransfer $customer
    ): AddressTransfer {
        if ($addressTransfer->getFkCustomer() === null) {
            $addressTransfer->setFkCustomer($customer->getIdCustomer());
        }

        $addressTransferKey = $this->customerService->getUniqueAddressKey($addressTransfer);
        if ($this->isAddressNewAndUnique($addressTransfer, $addressTransferKey)) {
            return $addressTransfer;
        }

        $this->processCustomerAddress($addressTransfer, $customer);
        $this->existingAddresses[$addressTransferKey] = true;

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param string $addressTransferKey
     *
     * @return bool
     */
    protected function isAddressNewAndUnique(AddressTransfer $addressTransfer, string $addressTransferKey): bool
    {
        return $addressTransfer->getIdCustomerAddress() !== null
            || isset($this->existingAddresses[$addressTransferKey]);
    }
}
