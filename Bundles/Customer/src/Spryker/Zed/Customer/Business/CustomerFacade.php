<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Business;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CustomerBusinessFactory getFactory()
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
        return $this->getFactory()
            ->createCustomer()
            ->hasEmail($email);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->register($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->confirmRegistration($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     *
     * @deprecated Use sendPasswordRestoreMail() instead
     */
    public function forgotPassword(CustomerTransfer $customerTransfer)
    {
        return $this->sendPasswordRestoreMail($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function sendPasswordRestoreMail(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->sendPasswordRestoreMail($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->restorePassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->delete($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->get($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function updateCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->update($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function updateCustomerPassword(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->updatePassword($customerTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->getAddress($addressTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    public function getAddresses(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->getAddresses($customerTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function updateAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->updateAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateAddressAndCustomerDefaultAddresses(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->updateAddressAndCustomerDefaultAddresses($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createAddressAndUpdateCustomerDefaultAddresses(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->createAddressAndUpdateCustomerDefaultAddresses($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function createAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->createAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function setDefaultBillingAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->setDefaultBillingAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function setDefaultShippingAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->setDefaultShippingAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return string
     */
    public function renderAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->getFormattedAddressString($addressTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function getDefaultShippingAddress(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->getDefaultShippingAddress($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function getDefaultBillingAddress(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->getDefaultBillingAddress($customerTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function deleteAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createAddress()
            ->deleteAddress($addressTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function tryAuthorizeCustomerByEmailAndPassword(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->tryAuthorizeCustomerByEmailAndPassword($customerTransfer);
    }

}
