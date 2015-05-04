<?php

namespace SprykerFeature\Sdk\Customer;

use SprykerEngine\Sdk\Kernel\AbstractSdk;
use Generated\Shared\Transfer\CustomerCustomer as CustomerTransferTransfer;
use Generated\Shared\Transfer\CustomerAddress as AddressTransferTransfer;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class CustomerSdk extends AbstractSdk
{
    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->register($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->confirmRegistration($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function forgotPassword(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->forgotPassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->restorePassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->delete($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function getCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->get($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function updateCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->update($customerTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->getAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function updateAddress(AddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->updateAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function createAddress(AddressTransfer $addressTransfer)
    {
        return $this->getDependencyContainer()->createModelCustomer()->createAddress($addressTransfer);
    }
}
