<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Customer\Service;

use Generated\Shared\Customer\CustomerAddressInterface;
use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class CustomerClient extends AbstractClient implements CustomerClientInterface
{

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
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function createAddress(CustomerAddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->createAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
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
    public function forgotPassword(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->forgotPassword($customerTransfer)
        ;
    }

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function getAddress(CustomerAddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->getAddress($addressTransfer)
        ;
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
     * @return bool
     */
    public function hasCustomerWithEmailAndPassword(CustomerInterface $customerTransfer)
    {
        $result = $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->hasCustomerWithEmailAndPassword($customerTransfer)
        ;

        $hasCustomer = $result->getHasCustomer();
        if (true === $hasCustomer) {
            $this->setCustomer($result->getCustomerTransfer());
        }

        return $hasCustomer;
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
     * @return CustomerTransfer
     */
    public function login(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->login($customerTransfer)
        ;
    }

    /**
     * @return bool
     */
    public function logout()
    {
        return $this->getDependencyContainer()
            ->createSessionCustomerSession()
            ->logout()
        ;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
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
    public function restorePassword(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->restorePassword($customerTransfer)
        ;
    }

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function updateAddress(CustomerAddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->updateAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function updateCustomer(CustomerInterface $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->update($customerTransfer)
        ;
    }

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function deleteAddress(CustomerAddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->deleteAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function setDefaultShippingAddress(CustomerAddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->setDefaultShippingAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerAddressInterface $addressTransfer
     *
     * @return CustomerAddressInterface
     */
    public function setDefaultBillingAddress(CustomerAddressInterface $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedCustomerStub()
            ->setDefaultBillingAddress($addressTransfer)
        ;
    }
}
