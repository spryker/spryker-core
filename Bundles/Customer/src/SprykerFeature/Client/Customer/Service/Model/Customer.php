<?php

namespace SprykerFeature\Client\Customer\Service\Model;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Client\ZedRequest\Service\Provider\ZedClientProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use SprykerFeature\Client\ZedRequest\Service\Client\Response;

class Customer
{
    /**
     * @var ZedClientProvider
     */
    protected $zedClient;

    /**
     * @param ZedClientProvider $zedClient
     */
    public function __construct(ZedClientProvider $zedClient)
    {
        $this->zedClient = $zedClient;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function register(CustomerTransfer $customerTransfer)
    {
        $encoder = new MessageDigestPasswordEncoder();
        $customerTransfer->setPassword($encoder->encodePassword($customerTransfer->getPassword(), ''));

        return $this->zedClient->createClient()->call('/customer/gateway/register', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        return $this->zedClient->createClient()->call('/customer/gateway/confirm-registration', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function forgotPassword(CustomerTransfer $customerTransfer)
    {
        return $this->zedClient->createClient()->call('/customer/gateway/forgot-password', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        $encoder = new MessageDigestPasswordEncoder();
        $customerTransfer->setPassword($encoder->encodePassword($customerTransfer->getPassword(), ''));
        return $this->zedClient->createClient()->call('/customer/gateway/restore-password', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return Response
     */
    public function delete(CustomerTransfer $customerTransfer)
    {
        return $this->zedClient->createClient()->call('/customer/gateway/delete', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function get(CustomerTransfer $customerTransfer)
    {
        return $this->zedClient->createClient()->call('/customer/gateway/customer', $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function update(CustomerTransfer $customerTransfer)
    {
        return $this->zedClient->createClient()->call('/customer/gateway/update', $customerTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return Response
     */
    public function updateAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->zedClient->createClient()->call('/customer/gateway/update-address', $addressTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function getAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->zedClient->createClient()->call('/customer/gateway/address', $addressTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function createAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->zedClient->createClient()->call('/customer/gateway/new-address', $addressTransfer);
    }
}
