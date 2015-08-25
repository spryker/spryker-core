<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use Generated\Shared\Customer\CustomerAddressInterface;
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
     * @param CustomerInterface $customerTransfer
     * 
     * @return CustomerInterface
     */
    public function getOrders(CustomerInterface $customerTransfer);

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function getAddress(CustomerAddressInterface $addressTransfer);

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function updateAddress(CustomerAddressInterface $addressTransfer);

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function createAddress(CustomerAddressInterface $addressTransfer);

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function deleteAddress(CustomerAddressInterface $addressTransfer);

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function setDefaultShippingAddress(CustomerAddressInterface $addressTransfer);

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function setDefaultBillingAddress(CustomerAddressInterface $addressTransfer);

}
