<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service\Zed;

use Generated\Shared\Customer\CustomerInterface as CustomerTransferInterface;
use Generated\Shared\Customer\CustomerAddressInterface as CustomerAddressTransferInterface;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

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
     * @inheritdoc
     */
    public function register(CustomerTransferInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/register', $customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function confirmRegistration(CustomerTransferInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/confirm-registration', $customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function forgotPassword(CustomerTransferInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/forgot-password', $customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function restorePassword(CustomerTransferInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/restore-password', $customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function hasCustomerWithEmailAndPassword(CustomerTransferInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/has-customer-with-email-and-password', $customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function delete(CustomerTransferInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/delete', $customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function get(CustomerTransferInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/customer', $customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function update(CustomerTransferInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/update', $customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function getOrders(CustomerTransferInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/get-orders', $customerTransfer);
    }

    /**
     * @inheritdoc
     */
    public function updateAddress(CustomerAddressTransferInterface $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/update-address', $addressTransfer);
    }

    /**
     * @inheritdoc
     */
    public function getAddress(CustomerAddressTransferInterface $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/address', $addressTransfer);
    }

    /**
     * @inheritdoc
     */
    public function createAddress(CustomerAddressTransferInterface $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/new-address', $addressTransfer);
    }

    /**
     * @inheritdoc
     */
    public function deleteAddress(CustomerAddressTransferInterface $customerAddressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/delete-address', $customerAddressTransfer);
    }

    /**
     * @inheritdoc
     */
    public function setDefaultBillingAddress(CustomerAddressTransferInterface $customerAddressInterface)
    {
        return $this->zedStub->call('/customer/gateway/default-billing-address', $customerAddressInterface);
    }

    /**
     * @inheritdoc
     */
    public function setDefaultShippingAddress(CustomerAddressTransferInterface $customerAddressInterface)
    {
        return $this->zedStub->call('/customer/gateway/default-shipping-address', $customerAddressInterface);
    }

}
