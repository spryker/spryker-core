<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Customer;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\ZedRequest\Client\Response;

/**
 * @method CustomerFactory getFactory()
 */
class CustomerClient extends AbstractClient implements CustomerClientInterface
{

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function hasCustomerWithEmailAndPassword(CustomerTransfer $customerTransfer)
    {
        $customerResponseTransfer = $this->getFactory()
            ->createZedCustomerStub()
            ->hasCustomerWithEmailAndPassword($customerTransfer);

        $hasCustomer = $customerResponseTransfer->getHasCustomer();
        if ($hasCustomer === true) {
            $this->setCustomer($customerResponseTransfer->getCustomerTransfer());
        }

        return $hasCustomer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer|null
     */
    public function findCustomerWithEmailAndPassword(CustomerTransfer $customerTransfer)
    {
        $customerResponseTransfer = $this->getFactory()
            ->createZedCustomerStub()
            ->hasCustomerWithEmailAndPassword($customerTransfer);

        return $customerResponseTransfer->getCustomerTransfer();
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
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
            ->createZedCustomerStub()
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
        trigger_error('Deprecated, use sendPasswordRestoreMail() instead.', E_USER_DEPRECATED);

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
            ->createZedCustomerStub()
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
            ->createZedCustomerStub()
            ->restorePassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomer(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->getFactory()
            ->createSessionCustomerSession()
            ->setCustomer($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomer()
    {
        $customerTransfer = $this->getFactory()
            ->createSessionCustomerSession()
            ->getCustomer();

        return $customerTransfer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByEmail(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->getFactory()
            ->createZedCustomerStub()
            ->get($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function updateCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
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
            ->createZedCustomerStub()
            ->updatePassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Client\ZedRequest\Client\Response
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
            ->delete($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer|null
     */
    public function login(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->findCustomerWithEmailAndPassword($customerTransfer);

        if ($customerTransfer !== null) {
            $this->getFactory()
                ->createSessionCustomerSession()
                ->setCustomer($customerTransfer);
        }

        return $customerTransfer;
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        return $this->getFactory()
            ->createSessionCustomerSession()
            ->logout();
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->getFactory()
            ->createSessionCustomerSession()
            ->hasCustomer();
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    public function getAddresses(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
            ->getAddresses($customerTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
            ->getAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function updateAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
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
            ->createZedCustomerStub()
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
            ->createZedCustomerStub()
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
            ->createZedCustomerStub()
            ->createAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function deleteAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
            ->deleteAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function setDefaultShippingAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
            ->setDefaultShippingAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function setDefaultBillingAddress(AddressTransfer $addressTransfer)
    {
        return $this->getFactory()
            ->createZedCustomerStub()
            ->setDefaultBillingAddress($addressTransfer);
    }

}
