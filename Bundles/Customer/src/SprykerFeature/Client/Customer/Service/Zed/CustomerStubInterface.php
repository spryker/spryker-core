<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Customer\Service\Zed;

use Generated\Shared\Customer\CustomerInterface as CustomerTransferInterface;
use Generated\Shared\Customer\CustomerAddressInterface as CustomerAddressTransferInterface;
use SprykerFeature\Client\ZedRequest\Service\Client\Response;

interface CustomerStubInterface
{

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function register(CustomerTransferInterface $customerTransfer);

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
     * @return Response
     */
    public function delete(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function get(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function update(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function getOrders(CustomerTransferInterface $customerTransfer);

    /**
     * @param CustomerAddressTransferInterface $addressTransfer
     *
     * @return Response
     */
    public function updateAddress(CustomerAddressTransferInterface $addressTransfer);

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
    public function createAddress(CustomerAddressTransferInterface $addressTransfer);

    /**
     * @param CustomerAddressTransferInterface $customerAddressTransfer
     *
     * @return CustomerAddressTransferInterface
     */
    public function deleteAddress(CustomerAddressTransferInterface $customerAddressTransfer);

    /**
     * @param CustomerAddressTransferInterface $customerAddressInterface
     *
     * @return CustomerAddressTransferInterface
     */
    public function setDefaultBillingAddress(CustomerAddressTransferInterface $customerAddressInterface);

    /**
     * @param CustomerAddressTransferInterface $customerAddressInterface
     *
     * @return CustomerAddressTransferInterface
     */
    public function setDefaultShippingAddress(CustomerAddressTransferInterface $customerAddressInterface);

}
