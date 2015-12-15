<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Customer\Zed;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\ZedRequest\Client\Response;
use Spryker\Client\ZedRequest\ZedRequestClient;

class CustomerStub implements CustomerStubInterface
{

    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function hasCustomerWithEmailAndPassword(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/has-customer-with-email-and-password', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function forgotPassword(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/forgot-password', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/restore-password', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/confirm-registration', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function register(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/register', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function get(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/customer', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function update(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/update', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function updatePassword(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/update-password', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return Response
     */
    public function delete(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/delete', $customerTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function createAddress(AddressTransfer $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/new-address', $addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function updateAddress(AddressTransfer $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/update-address', $addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return CustomerTransfer
     */
    public function updateAddressAndCustomerDefaultAddresses(AddressTransfer $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/update-address-and-customer-default-addresses', $addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return CustomerTransfer
     */
    public function createAddressAndUpdateCustomerDefaultAddresses(AddressTransfer $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/create-address-and-update-customer-default-addresses', $addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/address', $addressTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return AddressesTransfer
     */
    public function getAddresses(CustomerTransfer $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/addresses', $customerTransfer);
    }

    /**
     * @param AddressTransfer $AddressTransfer
     *
     * @return AddressTransfer
     */
    public function deleteAddress(AddressTransfer $AddressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/delete-address', $AddressTransfer);
    }

    /**
     * @param AddressTransfer $AddressTransfer
     *
     * @return AddressTransfer
     */
    public function setDefaultBillingAddress(AddressTransfer $AddressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/default-billing-address', $AddressTransfer);
    }

    /**
     * @param AddressTransfer $AddressTransfer
     *
     * @return AddressTransfer
     */
    public function setDefaultShippingAddress(AddressTransfer $AddressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/default-shipping-address', $AddressTransfer);
    }

}
