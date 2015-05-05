<?php

namespace SprykerFeature\Sdk\Customer;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerCustomerTransfer;
use SprykerEngine\Sdk\Kernel\AbstractSdk;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class CustomerSdk extends AbstractSdk
{
    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function registerCustomer(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->register($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function confirmRegistration(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->confirmRegistration($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function forgotPassword(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->forgotPassword($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function restorePassword(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->restorePassword($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function deleteCustomer(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->delete($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function getCustomer(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->get($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function updateCustomer(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->update($customerTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function getAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->getAddress($addressTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function updateAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->updateAddress($addressTransfer);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function createAddress(CustomerAddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->createAddress($addressTransfer);
    }
}
