<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use Generated\Shared\Customer\AddressInterface;
use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\ZedRequest\Service\Client\Response;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class CustomerClient extends AbstractClient implements CustomerClientInterface
{

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return bool
     */
    public function hasCustomerWithEmailAndPassword(CustomerInterface $customerTransfer)
    {
        $customerResponseTransfer = $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->hasCustomerWithEmailAndPassword($customerTransfer)
        ;

        $hasCustomer = $customerResponseTransfer->getHasCustomer();
        if ($hasCustomer === true) {
            $this->setCustomer($customerResponseTransfer->getCustomerTransfer());
        }

        return $hasCustomer;
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return CustomerResponseTransfer
     */
    public function registerCustomer(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->register($customerTransfer)
        ;
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return CustomerInterface
     */
    public function confirmRegistration(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->confirmRegistration($customerTransfer)
        ;
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return CustomerResponseTransfer
     */
    public function forgotPassword(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->forgotPassword($customerTransfer)
        ;
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return CustomerResponseTransfer
     */
    public function restorePassword(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->restorePassword($customerTransfer)
        ;
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return CustomerInterface
     */
    public function setCustomer(CustomerInterface $customerTransfer)
    {
        $customerTransfer = $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->setCustomer($customerTransfer)
        ;

        return $customerTransfer;
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        $customerTransfer = $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->getCustomer()
        ;

        return $customerTransfer;
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return CustomerInterface
     */
    public function getCustomerByEmail(CustomerInterface $customerTransfer)
    {
        $customerTransfer = $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->get($customerTransfer)
        ;

        return $customerTransfer;
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return CustomerResponseTransfer
     */
    public function updateCustomer(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->update($customerTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function updateCustomerPassword(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->updatePassword($customerTransfer);
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return Response
     */
    public function deleteCustomer(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->delete($customerTransfer)
        ;
    }

    /**
     * @param CustomerInterface $customerTransfer
     * 
     * @return CustomerInterface
     */
    public function login(CustomerInterface $customerTransfer)
    {
        $customerTransfer = $this->getCustomerByEmail($customerTransfer);
        $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->setCustomer($customerTransfer)
        ;

        return $customerTransfer;
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        return $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->logout()
        ;
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->hasCustomer()
        ;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return AddressesTransfer
     */
    public function getAddresses(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->getAddresses($customerTransfer)
        ;
    }

    /**
     * @param AddressInterface $addressTransfer
     * 
     * @return AddressInterface
     */
    public function getAddress(AddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->getAddress($addressTransfer)
        ;
    }

    /**
     * @param AddressInterface $addressTransfer
     * 
     * @return AddressInterface
     */
    public function updateAddress(AddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->updateAddress($addressTransfer)
        ;
    }

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return CustomerInterface
     */
    public function updateAddressAndCustomerDefaultAddresses(AddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->updateAddressAndCustomerDefaultAddresses($addressTransfer)
        ;
    }

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return CustomerInterface
     */
    public function createAddressAndUpdateCustomerDefaultAddresses(AddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->createAddressAndUpdateCustomerDefaultAddresses($addressTransfer)
        ;
    }

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function createAddress(AddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->createAddress($addressTransfer)
        ;
    }

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function deleteAddress(AddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->deleteAddress($addressTransfer)
        ;
    }

    /**
     * @param AddressInterface $addressTransfer
     *
     * @return AddressInterface
     */
    public function setDefaultShippingAddress(AddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->setDefaultShippingAddress($addressTransfer)
        ;
    }

    /**
     * @param AddressInterface $addressTransfer
     * 
     * @return AddressInterface
     */
    public function setDefaultBillingAddress(AddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->setDefaultBillingAddress($addressTransfer)
        ;
    }

}
