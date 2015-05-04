<?php

namespace SprykerFeature\Sdk\Customer\Model;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Shared\Transfer\CustomerCustomer as CustomerTransferTransfer;
use Generated\Shared\Transfer\CustomerAddress as AddressTransferTransfer;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use SprykerFeature\Sdk\ZedRequest\Client\Response;
use Generated\Zed\Ide\AutoCompletion;

class Customer
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param FactoryInterface $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(FactoryInterface $factory, LocatorLocatorInterface $locator)
    {
        $this->factory = $factory;
        $this->locator = $locator;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function register(CustomerTransfer $customerTransfer)
    {
        $encoder = new MessageDigestPasswordEncoder();
        $customerTransfer->setPassword($encoder->encodePassword($customerTransfer->getPassword(), ""));

        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/register", $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/confirm-registration", $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function forgotPassword(CustomerTransfer $customerTransfer)
    {
        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/forgot-password", $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        $encoder = new MessageDigestPasswordEncoder();
        $customerTransfer->setPassword($encoder->encodePassword($customerTransfer->getPassword(), ""));
        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/restore-password", $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return Response
     */
    public function delete(CustomerTransfer $customerTransfer)
    {
        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/delete", $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function get(CustomerTransfer $customerTransfer)
    {
        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/customer", $customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function update(CustomerTransfer $customerTransfer)
    {
        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/update", $customerTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return Response
     */
    public function updateAddress(AddressTransfer $addressTransfer)
    {
        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/update-address", $addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer)
    {
        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/address", $addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function createAddress(AddressTransfer $addressTransfer)
    {
        return $this->locator->zedRequest()->zedClient()->createClient()->call("/customer/sdk/new-address", $addressTransfer);
    }
}
