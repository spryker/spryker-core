<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use Generated\Shared\Customer\CustomerInterface as CustomerTransferInterface;
use Generated\Shared\Customer\CustomerAddressInterface as CustomerAddressTransferInterface;

interface CustomerClientInterface
{

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function registerCustomer(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function confirmRegistration(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function forgotPassword(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function restorePassword(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function deleteCustomer(CustomerTransferInterface $customerTransfer);

    /**
     * @return CustomerTransferInterface
     */
    public function getCustomer();

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function setCustomer(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function login(CustomerTransferInterface $customerTransfer);

    /**
     * @return mixed
     */
    public function logout();

    /**
     * @return bool
     */
    public function isLoggedIn();

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function getCustomerByEmail(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function updateCustomer(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     * 
     * @return CustomerTransferInterface
     */
    public function getLatestOrders(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     * 
     * @return CustomerTransferInterface
     */
    public function getOrders(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerAddressTransferInterface $addressTransfer
     *
     * @return CustomerAddressTransferInterface
     */
    public function getAddress(CustomerAddressTransferInterface $addressTransfer);

    /**
     * @param CustomerAddressTransferInterface $addressTransfer
     *
     * @return CustomerAddressTransferInterface
     */
    public function updateAddress(CustomerAddressTransferInterface $addressTransfer);

    /**
     * @param CustomerAddressTransferInterface $addressTransfer
     *
     * @return CustomerAddressTransferInterface
     */
    public function createAddress(CustomerAddressTransferInterface $addressTransfer);

    /**
     * @param CustomerAddressTransferInterface $addressTransfer
     *
     * @return CustomerAddressTransferInterface
     */
    public function deleteAddress(CustomerAddressTransferInterface $addressTransfer);

    /**
     * @param CustomerAddressTransferInterface $addressTransfer
     *
     * @return CustomerAddressTransferInterface
     */
    public function setDefaultShippingAddress(CustomerAddressTransferInterface $addressTransfer);

    /**
     * @param CustomerAddressTransferInterface $addressTransfer
     *
     * @return CustomerAddressTransferInterface
     */
    public function setDefaultBillingAddress(CustomerAddressTransferInterface $addressTransfer);

}
