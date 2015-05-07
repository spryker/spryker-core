<?php

namespace SprykerFeature\Zed\Customer\Business;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerCustomerTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class CustomerFacade extends AbstractFacade
{
    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function registerCustomer(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->register($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function confirmRegistration(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->confirmRegistration($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function forgotPassword(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->forgotPassword($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function restorePassword(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->restorePassword($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function deleteCustomer(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->delete($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function getCustomer(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->get($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function updateCustomer(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->update($customerTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function getAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->getAddress($addressTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function updateAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->updateAddress($addressTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function newAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->newAddress($addressTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function setDefaultBillingAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->setDefaultBillingAddress($addressTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function setDefaultShippingAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->setDefaultShippingAddress($addressTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return string
     */
    public function renderAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->getFormattedAddressString($addressTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     * @return CustomerAddressTransfer
     */
    public function getDefaultShippingAddress(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->getDefaultShippingAddress($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function getDefaultBillingAddress(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->getDefaultBillingAddress($customerTransfer);
    }
}
