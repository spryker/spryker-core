<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Customer\Service\Zed;

use Generated\Shared\Customer\AddressInterface;
use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Client\ZedRequest\Service\Client\Response;

interface CustomerStubInterface
{
    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function hasCustomerWithEmailAndPassword(CustomerInterface $customerTransfer);

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
    public function confirmRegistration(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function register(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function get(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function update(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function updatePassword(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return Response
     */
    public function delete(CustomerInterface $customerTransfer);

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
    public function updateAddress(AddressInterface $addressTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return CustomerInterface
     */
    public function updateAddressAndCustomerDefaultAddresses(AddressInterface $addressTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return CustomerInterface
     */
    public function createAddressAndUpdateCustomerDefaultAddresses(AddressInterface $addressTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function getAddress(AddressInterface $addressTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return AddressesTransfer
     */
    public function getAddresses(CustomerInterface $customerTransfer);

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function deleteAddress(AddressInterface $addressTransfer);

    /**
     * @param AddressInterface $AddressInterface
     *
     * @return AddressInterface
     */
    public function setDefaultBillingAddress(AddressInterface $AddressInterface);

    /**
     * @param AddressInterface $AddressInterface
     *
     * @return AddressInterface
     */
    public function setDefaultShippingAddress(AddressInterface $AddressInterface);

}
