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
use Generated\Shared\Transfer\SaveOrderTransfer;
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
        parent::__construct($customer, $address);

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
        /**
         * @deprecated Will be removed in next major version after multiple shipment release.
         */
        $quoteTransfer = $this->adaptQuoteDataBCForMultiShipment($quoteTransfer);

        parent::saveOrderCustomer($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function adaptQuoteDataBCForMultiShipment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$this->assertThatItemTransfersHasShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        if (!$this->assertThatQuoteHasAddressTransfer($quoteTransfer)) {
            return $quoteTransfer;
        }

        if (!$this->assertThatQuoteHasShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->assertThatItemTransferHasShipmentWithShippingAddress($itemTransfer)) {
                continue;
            }

            $this->setItemTransferShipmentAndShipmentAddressForBC($itemTransfer, $quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransfersHasShipment(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() !== null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasAddressTransfer(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShippingAddress() !== null;
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasShipment(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment() !== null;
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransferHasShipmentWithShippingAddress(ItemTransfer $itemTransfer): bool
    {
        return ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getShippingAddress() !== null);
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getShipmentTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ShipmentTransfer
    {
        if ($itemTransfer->getShipment() !== null) {
            return $itemTransfer->getShipment();
        }

        if ($quoteTransfer->getShipment() !== null) {
            return $quoteTransfer->getShipment();
        }

        return new ShipmentTransfer();
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getShipmentAddressTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): AddressTransfer
    {
        if ($itemTransfer->getShipment()->getShippingAddress() !== null) {
            return $itemTransfer->getShipment()->getShippingAddress();
        }

        return $quoteTransfer->getShipment()->getShippingAddress();
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setItemTransferShipmentAndShipmentAddressForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): void
    {
        $shippingAddressTransfer = $this->getShipmentAddressTransferForBC($itemTransfer, $quoteTransfer);
        $shipmentTransfer = $this->getShipmentTransferForBC($itemTransfer, $quoteTransfer);
        $shipmentTransfer->setShippingAddress($shippingAddressTransfer);
        $itemTransfer->setShipment($shipmentTransfer);
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
    protected function getCustomerAddress(ShipmentTransfer $shipmentTransfer): AddressTransfer
    {
        $addressTransfer = $shipmentTransfer->getShippingAddress();

        $key = $this->getAddressTransferKey($addressTransfer);
        if (!isset($this->existingAddresses[$key])) {
            $this->existingAddresses[$key] = $this->customerRepository->findAddressByAddressData($addressTransfer);
        }
        if ($this->existingAddresses[$key] !== null) {
            return $this->existingAddresses[$key];
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
        return implode(' ', $addressTransfer->toArray());
    }
}
