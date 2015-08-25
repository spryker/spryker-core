<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Customer\Service\Zed;

use Generated\Shared\Customer\CustomerAddressInterface;
use Generated\Shared\Customer\CustomerInterface;
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
     * @return CustomerInterface
     */
    public function update(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return Response
     */
    public function delete(CustomerInterface $customerTransfer);

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function createAddress(CustomerAddressInterface $addressTransfer);

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return Response
     */
    public function updateAddress(CustomerAddressInterface $addressTransfer);

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function getAddress(CustomerAddressInterface $addressTransfer);

    /**
     * @param CustomerAddressInterface $customerAddressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function deleteAddress(CustomerAddressInterface $customerAddressTransfer);

    /**
     * @param CustomerAddressInterface $customerAddressInterface
     *
     * @return CustomerAddressInterface
     */
    public function setDefaultBillingAddress(CustomerAddressInterface $customerAddressInterface);

    /**
     * @param CustomerAddressInterface $customerAddressInterface
     *
     * @return CustomerAddressInterface
     */
    public function setDefaultShippingAddress(CustomerAddressInterface $customerAddressInterface);

}
