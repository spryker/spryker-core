<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;

interface CustomerClientInterface
{

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function hasCustomerWithEmailAndPassword(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer|null
     */
    public function findCustomerWithEmailAndPassword(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function forgotPassword(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer);

    /**
     * @return CustomerTransfer
     */
    public function getCustomer();

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function setCustomer(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function login(CustomerTransfer $customerTransfer);

    /**
     * @return mixed
     */
    public function logout();

    /**
     * @return bool
     */
    public function isLoggedIn();

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return AddressesTransfer
     */
    public function getAddresses(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function getCustomerByEmail(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function updateCustomer(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function updateCustomerPassword(CustomerTransfer $customerTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer);

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
    public function createAddress(AddressTransfer $addressTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function deleteAddress(AddressTransfer $addressTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function setDefaultShippingAddress(AddressTransfer $addressTransfer);

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function setDefaultBillingAddress(AddressTransfer $addressTransfer);

}
