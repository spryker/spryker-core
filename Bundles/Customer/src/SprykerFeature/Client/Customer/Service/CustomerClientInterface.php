<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Customer\CustomerAddressInterface;
use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerClientInterface
{

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
     * @return CustomerTransfer
     */
    public function login(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return bool
     */
    public function hasCustomerWithEmailAndPassword(CustomerInterface $customerTransfer);

    /**
     * @return bool
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
     * @param CustomerInterface $customerTransfer
     * @return array
     */
    public function getLatestCartOrders(CustomerInterface $customerTransfer);

}
