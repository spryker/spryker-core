<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Zed;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class CustomerStub implements CustomerStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function hasCustomerWithEmailAndPassword(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer */
        $customerResponseTransfer = $this->zedStub->call('/customer/gateway/has-customer-with-email-and-password', $customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function sendPasswordRestoreMail(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer */
        $customerResponseTransfer = $this->zedStub->call('/customer/gateway/send-password-restore-mail', $customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer */
        $customerResponseTransfer = $this->zedStub->call('/customer/gateway/restore-password', $customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $this->zedStub->call('/customer/gateway/confirm-registration', $customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function register(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer */
        $customerResponseTransfer = $this->zedStub->call('/customer/gateway/register', $customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function get(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $this->zedStub->call('/customer/gateway/customer', $customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function update(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer */
        $customerResponseTransfer = $this->zedStub->call('/customer/gateway/update', $customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function updatePassword(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer */
        $customerResponseTransfer = $this->zedStub->call('/customer/gateway/update-password', $customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Client\ZedRequest\Client\Response
     */
    public function delete(CustomerTransfer $customerTransfer)
    {
        /** @var \Spryker\Client\ZedRequest\Client\Response $response */
        $response = $this->zedStub->call('/customer/gateway/delete', $customerTransfer);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function createAddress(AddressTransfer $addressTransfer)
    {
        /** @var \Generated\Shared\Transfer\AddressTransfer $addressTransfer */
        $addressTransfer = $this->zedStub->call('/customer/gateway/new-address', $addressTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function updateAddress(AddressTransfer $addressTransfer)
    {
        /** @var \Generated\Shared\Transfer\AddressTransfer $addressTransfer */
        $addressTransfer = $this->zedStub->call('/customer/gateway/update-address', $addressTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateAddressAndCustomerDefaultAddresses(AddressTransfer $addressTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $this->zedStub->call('/customer/gateway/update-address-and-customer-default-addresses', $addressTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createAddressAndUpdateCustomerDefaultAddresses(AddressTransfer $addressTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $this->zedStub->call('/customer/gateway/create-address-and-update-customer-default-addresses', $addressTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer)
    {
        /** @var \Generated\Shared\Transfer\AddressTransfer $addressTransfer */
        $addressTransfer = $this->zedStub->call('/customer/gateway/address', $addressTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    public function getAddresses(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer */
        $addressesTransfer = $this->zedStub->call('/customer/gateway/addresses', $customerTransfer);

        return $addressesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $AddressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function deleteAddress(AddressTransfer $AddressTransfer)
    {
        /** @var \Generated\Shared\Transfer\AddressTransfer $addressTransfer */
        $addressTransfer = $this->zedStub->call('/customer/gateway/delete-address', $AddressTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $AddressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function setDefaultBillingAddress(AddressTransfer $AddressTransfer)
    {
        /** @var \Generated\Shared\Transfer\AddressTransfer $addressTransfer */
        $addressTransfer = $this->zedStub->call('/customer/gateway/default-billing-address', $AddressTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $AddressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function setDefaultShippingAddress(AddressTransfer $AddressTransfer)
    {
        /** @var \Generated\Shared\Transfer\AddressTransfer $addressTransfer */
        $addressTransfer = $this->zedStub->call('/customer/gateway/default-shipping-address', $AddressTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function anonymizeCustomer(CustomerTransfer $customerTransfer)
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $this->zedStub->call('/customer/gateway/anonymize-customer', $customerTransfer);

        return $customerTransfer;
    }
}
