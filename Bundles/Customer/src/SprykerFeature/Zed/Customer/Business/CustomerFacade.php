<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class CustomerFacade extends AbstractFacade
{

    /**
     * @param string $email
     *
     * @return bool
     */
    public function hasEmail($email)
    {
        return $this->getDependencyContainer()
            ->createCustomer()
            ->hasEmail($email)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createCustomer()
            ->register($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createCustomer()
            ->confirmRegistration($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function forgotPassword(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createCustomer()
            ->forgotPassword($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createCustomer()
            ->restorePassword($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createCustomer()
            ->delete($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function getCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createCustomer()
            ->get($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function updateCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createCustomer()
            ->update($customerTransfer)
        ;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function getAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createAddress()
            ->getAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function updateAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createAddress()
            ->updateAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function createAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createAddress()
            ->createAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function setDefaultBillingAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createAddress()
            ->setDefaultBillingAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function setDefaultShippingAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createAddress()
            ->setDefaultShippingAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return string
     */
    public function renderAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createAddress()
            ->getFormattedAddressString($addressTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function getDefaultShippingAddress(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createAddress()
            ->getDefaultShippingAddress($customerTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function getDefaultBillingAddress(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createAddress()
            ->getDefaultBillingAddress($customerTransfer)
        ;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function deleteAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()
            ->createAddress()
            ->deleteAddress($addressTransfer)
        ;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return array
     */
    public function getLatestCartOrders(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()
            ->createCustomer()
            ->getLatestCartOrders($customerTransfer)
            ;
    }

}
