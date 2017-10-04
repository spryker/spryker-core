<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Zed;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerStubInterface
{

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function hasCustomerWithEmailAndPassword(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function sendPasswordRestoreMail(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function restorePassword(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function register(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function get(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function update(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updatePassword(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Client\ZedRequest\Client\Response|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function delete(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createAddress(AddressTransfer $addressTransfer);

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateAddress(AddressTransfer $addressTransfer);

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateAddressAndCustomerDefaultAddresses(AddressTransfer $addressTransfer);

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createAddressAndUpdateCustomerDefaultAddresses(AddressTransfer $addressTransfer);

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getAddress(AddressTransfer $addressTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getAddresses(CustomerTransfer $customerTransfer);

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function deleteAddress(AddressTransfer $addressTransfer);

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $AddressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function setDefaultBillingAddress(AddressTransfer $AddressTransfer);

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $AddressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function setDefaultShippingAddress(AddressTransfer $AddressTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function anonymizeCustomer(CustomerTransfer $customerTransfer);

}
