<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Checkout;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Customer\Business\Customer\AddressInterface;
use Spryker\Zed\Customer\Business\Customer\CustomerInterface;
use Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface;

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
     * @param \Spryker\Zed\Customer\Business\Customer\CustomerInterface $customer
     * @param \Spryker\Zed\Customer\Business\Customer\AddressInterface $address
     * @param \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerInterface $customer,
        AddressInterface $address,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct();

        $this->customerRepository = $customerRepository;
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

        $this->processCustomerAddress($quoteTransfer->getBillingAddress(), $customer);

        $this->existingAddresses = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireShipment();

            $addressTransfer = $this->getCustomerAddress($itemTransfer->getShipment());
            $itemTransfer->getShipment()->setShippingAddress($addressTransfer);

            $this->processCustomerAddress($addressTransfer, $customer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getCustomerAddress(ShipmentTransfer $shipmentTransfer)
    {
        $addressTransfer = $shipmentTransfer->getShippingAddress();

        $key = $this->getAddressTransferKey($addressTransfer);
        if (!isset($existingAddresses[$key])) {
            $existingAddresses[$key] = $this->customerRepository->findAddressByAddressData($addressTransfer);
        }
        if ($existingAddresses[$key] !== null) {
            return $existingAddresses[$key];
        }

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getAddressTransferKey(AddressTransfer $addressTransfer): string
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
