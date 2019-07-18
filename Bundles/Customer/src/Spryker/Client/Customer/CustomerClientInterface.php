<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerClientInterface
{
    /**
     * Specification:
     * - Checks if customer exists in persistent storage by provided email and plain text password.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerWithEmailAndPassword(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Validates provided customer email information.
     * - Encrypts provided plain text password.
     * - Assigns current locale to customer if it is not set already.
     * - Generates customer reference for customer.
     * - Stores customer data.
     * - Sends specific registration confirmation link via email using a freshly generated registration key.
     * - Sends password restoration email if SendPasswordToken property is set in the provided transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Finds customer registration confirmation by provided registration key.
     * - Sets customer as registered and removes the registration key from persistent storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Sends password restoration link via email using a freshly generated password restoration key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function sendPasswordRestoreMail(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Identifies customer by either customer ID, customer email, or password restoration key.
     * - Encrypts provided plain text password.
     * - Stores new password for customer in persistent storage.
     * - Removes password restoration key from customer.
     * - Sends password restoration confirmation email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Deletes a customer entity by either customer ID, customer email, or password restoration key.
     * - Does not handle related connected entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Client\ZedRequest\Client\Response
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Returns customer information from session.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer();

    /**
     * Specification:
     * - Stores provided customer information in session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomer(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Stores provided customer information in session without executing plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomerRawData(CustomerTransfer $customerTransfer): CustomerTransfer;

    /**
     * Specification:
     * - Returns customer information from session without executing plugins.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerRawData(): ?CustomerTransfer;

    /**
     * Specification:
     * - Checks if customer exists in persistent storage by provided email and plain text password.
     * - Stores found customer information in session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function login(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Removes customer information from session.
     *
     * @api
     *
     * @return void
     */
    public function logout();

    /**
     * Specification:
     * - Checks if customer information is present in session.
     *
     * @api
     *
     * @return bool
     */
    public function isLoggedIn();

    /**
     * Specification:
     * - Retrieves provided customer related addresses from persistent storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    public function getAddresses(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Retrieves customer information with customer addresses by customer ID from persistent storage.
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerById($idCustomer);

    /**
     * Specification:
     * - Retrieves customer information using provided customer ID.
     * - Returns null if customer was not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerById(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Retrieves customer information by either customer ID, customer email, or password restoration key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByEmail(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Updates password if NewPassword property is set in provided transfer object:
     *      - Validates provided current plain text password using persistent storage.
     *      - Encrypts provided plain text password before update.
     * - Identifies customer by either customer ID, customer email, or password restoration key.
     * - Validates customer email information.
     * - Updates customer data which is set in provided transfer object.
     * - Sends password restoration email if SendPasswordToken property is set in the provided transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function updateCustomer(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Identifies customer by either customer ID, customer email, or password restoration key.
     * - Validates provided current plain text password using persistent storage.
     * - Encrypts provided plain text password and stores it in persistent storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function updateCustomerPassword(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Retrieves an address by customer ID and address ID.
     * - Populates address flags.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer);

    /**
     * Specification:
     * - Updates customer address using provided transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function updateAddress(AddressTransfer $addressTransfer);

    /**
     * Specification:
     * - Updates customer address using provided transfer object.
     * - Sets address as default address based on provided default address flags.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateAddressAndCustomerDefaultAddresses(AddressTransfer $addressTransfer);

    /**
     * Specification:
     * - Creates customer address using provided transfer object.
     * - Sets address as default address based on provided default address flags.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createAddressAndUpdateCustomerDefaultAddresses(AddressTransfer $addressTransfer);

    /**
     * Specification:
     * - Creates customer address using provided transfer object.
     * - Sets address as default address based on provided default address flags.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function createAddress(AddressTransfer $addressTransfer);

    /**
     * Specification:
     * - Deletes address.
     * - Removes references between customer-address entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function deleteAddress(AddressTransfer $addressTransfer);

    /**
     * Specification:
     * - Sets provided address as default shipping address for the related customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function setDefaultShippingAddress(AddressTransfer $addressTransfer);

    /**
     * Specification:
     * - Sets provided address as default billing address for the related customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function setDefaultBillingAddress(AddressTransfer $addressTransfer);

    /**
     * Specification:
     * - Identifies customer by either customer ID, customer email, or password restoration key.
     * - Applies configured CustomerAnonymizerPluginInterface plugins on customer data.
     * - Anonymizes customer addresses.
     * - Anonymizes customer data.
     * - Updates persistent storage with anonymized data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function anonymizeCustomer(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     * - Marks a customer as dirty.
     * - Customer will be reloaded from Zed with next request.
     *
     * @api
     *
     * @return void
     */
    public function markCustomerAsDirty();

    /**
     * Specification:
     * - Retrieves customer information using provided customer reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomerByReference(CustomerTransfer $customerTransfer): CustomerResponseTransfer;

    /**
     * Specification:
     * - Returns customer secured pattern with applied customer access rules.
     *
     * @api
     *
     * @return string
     */
    public function getCustomerSecuredPattern(): string;

    /**
     * Specification:
     * - Retrieves customer by access token using AccessTokenAuthenticationHandlerPluginInterface.
     *
     * @api
     *
     * @param string $accessToken
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function getCustomerByAccessToken(string $accessToken): CustomerResponseTransfer;
}
