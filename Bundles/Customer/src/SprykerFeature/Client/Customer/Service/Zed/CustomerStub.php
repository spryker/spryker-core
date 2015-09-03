<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service\Zed;

use Generated\Shared\Customer\AddressInterface;
use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Client\ZedRequest\Service\Client\Response;
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
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function hasCustomerWithEmailAndPassword(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/has-customer-with-email-and-password', $customerTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function forgotPassword(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/forgot-password', $customerTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function restorePassword(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/restore-password', $customerTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/confirm-registration', $customerTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function register(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/register', $customerTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function get(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/customer', $customerTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function update(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/update', $customerTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return Response
     */
    public function delete(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/delete', $customerTransfer);
    }

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressTransfer
     */
    public function createAddress(AddressInterface $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/new-address', $addressTransfer);
    }

    /**
     * @param AddressInterface $addressTransfer
     * 
     * @return AddressTransfer
     */
    public function updateAddress(AddressInterface $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/update-address', $addressTransfer);
    }

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressTransfer
     */
    public function getAddress(AddressInterface $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/address', $addressTransfer);
    }

    /**
     * @param AddressInterface $AddressTransfer
     * 
     * @return AddressTransfer
     */
    public function deleteAddress(AddressInterface $AddressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/delete-address', $AddressTransfer);
    }

    /**
<<<<<<< HEAD
     * @param AddressInterface $AddressInterface
     * 
     * @return AddressTransfer
=======
     * @param AddressInterface $AddressInterface
     *
     * @return AddressTransfer
>>>>>>> bbf21b9... KSP-134 Create shipping methods
     */
    public function setDefaultBillingAddress(AddressInterface $AddressInterface)
    {
        return $this->zedStub->call('/customer/gateway/default-billing-address', $AddressInterface);
    }


    /**
     * @param AddressInterface $AddressInterface
     *
     * @return AddressTransfer
     */
    public function setDefaultShippingAddress(AddressInterface $AddressInterface)
    {
        return $this->zedStub->call('/customer/gateway/default-shipping-address', $AddressInterface);
    }

}
