<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service\Zed;

use Generated\Shared\Customer\CustomerAddressInterface;
use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Client\ZedRequest\Service\Client\Response;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

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
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/confirm-registration', $customerTransfer);
    }

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function createAddress(CustomerAddressInterface $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/new-address', $addressTransfer);
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
    public function get(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/customer', $customerTransfer);
    }

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function getAddress(CustomerAddressInterface $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/address', $addressTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function login(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/login', $customerTransfer);
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
     * @return CustomerTransfer
     */
    public function restorePassword(CustomerInterface $customerTransfer)
    {
        return $this->zedStub->call('/customer/gateway/restore-password', $customerTransfer);
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
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return Response
     */
    public function updateAddress(CustomerAddressInterface $addressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/update-address', $addressTransfer);
    }

    /**
     * @param CustomerAddressInterface $customerAddressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function deleteAddress(CustomerAddressInterface $customerAddressTransfer)
    {
        return $this->zedStub->call('/customer/gateway/delete-address', $customerAddressTransfer);
    }

    /**
     * @param CustomerAddressInterface $customerAddressInterface
     *
     * @return CustomerAddressTransfer
     */
    public function setDefaultBillingAddress(CustomerAddressInterface $customerAddressInterface)
    {
        return $this->zedStub->call('/customer/gateway/default-billing-address', $customerAddressInterface);
    }

    /**
     * @param CustomerAddressInterface $customerAddressInterface
     *
     * @return CustomerAddressTransfer
     */
    public function setDefaultShippingAddress(CustomerAddressInterface $customerAddressInterface)
    {
        return $this->zedStub->call('/customer/gateway/default-shipping-address', $customerAddressInterface);
    }

}
