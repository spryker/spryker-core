<?php

namespace SprykerFeature\Zed\Customer\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Customer\Transfer\Customer as CustomerTransfer;
use SprykerFeature\Shared\Customer\Transfer\Address as AddressTransfer;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class CustomerFacade extends AbstractFacade
{
    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->register($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->confirmRegistration($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function forgotPassword(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->forgotPassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->restorePassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->delete($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function getCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->get($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function updateCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createCustomer()
            ->update($customerTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->getAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function updateAddress(AddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->updateAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function newAddress(AddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->newAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function setDefaultBillingAddress(AddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->setDefaultBillingAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function setDefaultShippingAddress(AddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->setDefaultShippingAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return string
     */
    public function renderAddress(AddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createAddress()
            ->getFormattedAddressString($addressTransfer);
    }
}
