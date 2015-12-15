<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Customer\Zed;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Client\ZedRequest\Client\Response;

interface CustomerStubInterface
{

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function hasCustomerWithEmailAndPassword(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function forgotPassword(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function register(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function get(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function update(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function updatePassword(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return Response
     */
    public function delete(CustomerTransfer $customerTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function createAddress(AddressTransfer $addressTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function updateAddress(AddressTransfer $addressTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return CustomerTransfer
     */
    public function updateAddressAndCustomerDefaultAddresses(AddressTransfer $addressTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return CustomerTransfer
     */
    public function createAddressAndUpdateCustomerDefaultAddresses(AddressTransfer $addressTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return AddressesTransfer
     */
    public function getAddresses(CustomerTransfer $customerTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function deleteAddress(AddressTransfer $addressTransfer);

    /**
     * @param AddressTransfer $AddressTransfer
     *
     * @return AddressTransfer
     */
    public function setDefaultBillingAddress(AddressTransfer $AddressTransfer);

    /**
     * @param AddressTransfer $AddressTransfer
     *
     * @return AddressTransfer
     */
    public function setDefaultShippingAddress(AddressTransfer $AddressTransfer);

}
