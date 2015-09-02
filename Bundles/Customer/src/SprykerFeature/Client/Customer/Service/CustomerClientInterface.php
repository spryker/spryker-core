<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use Generated\Shared\Customer\AddressInterface;
use Generated\Shared\Customer\CustomerInterface;

interface CustomerClientInterface
{

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return bool
     */
    public function hasCustomerWithEmailAndPassword(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function registerCustomer(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function confirmRegistration(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function forgotPassword(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function restorePassword(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function deleteCustomer(CustomerInterface $customerTransfer);

    /**
     * @return CustomerInterface
     */
    public function getCustomer();

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function setCustomer(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function login(CustomerInterface $customerTransfer);

    /**
     * @return mixed
     */
    public function logout();

    /**
     * @return bool
     */
    public function isLoggedIn();

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function getCustomerByEmail(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function updateCustomer(CustomerInterface $customerTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function getAddress(AddressInterface $addressTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function updateAddress(AddressInterface $addressTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function createAddress(AddressInterface $addressTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function deleteAddress(AddressInterface $addressTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function setDefaultShippingAddress(AddressInterface $addressTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function setDefaultBillingAddress(AddressInterface $addressTransfer);

}
