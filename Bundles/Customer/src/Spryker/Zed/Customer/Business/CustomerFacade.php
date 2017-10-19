<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerBusinessFactory getFactory()
 */
class CustomerFacade extends AbstractFacade implements CustomerFacadeInterface
{
    /**
     * @api
     *
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function addCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->add($customerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @deprecated Use CustomerFacade::sendPasswordRestoreMail() instead
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function forgotPassword(CustomerTransfer $customerTransfer)
    {
        return $this->sendPasswordRestoreMail($customerTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerById(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->findById($customerTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function tryAuthorizeCustomerByEmailAndPassword(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createCustomer()
            ->tryAuthorizeCustomerByEmailAndPassword($customerTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveCustomerForOrder(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer  $checkoutResponseTransfer
    ) {
        $this->getFactory()->createCustomerOrderSaver()->saveOrder($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function checkOrderPreSaveConditions(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer  $checkoutResponseTransfer
    ) {
        $this->getFactory()
            ->createPreConditionChecker()
            ->checkPreConditions($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function anonymizeCustomer(CustomerTransfer $customerTransfer)
    {
        $this->getFactory()
            ->createCustomerAnonymizer()
            ->process($customerTransfer);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findByReference($customerReference)
    {
        return $this->getFactory()
            ->createCustomer()
            ->findByReference($customerReference);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateSalesOrderCustomerInformation(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createCustomerOrderHydrator()->hydrateOrderTransfer($orderTransfer);
    }
}
