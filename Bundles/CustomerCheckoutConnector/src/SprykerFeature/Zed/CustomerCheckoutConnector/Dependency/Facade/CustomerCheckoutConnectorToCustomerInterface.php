<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerCheckoutConnectorToCustomerInterface
{

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function getCustomer(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function updateCustomer(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer);

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function createAddress(CustomerAddressTransfer $addressTransfer);

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function updateAddress(CustomerAddressTransfer $addressTransfer);

    /**
     * @param string $email
     *
     * @return bool
     */
    public function hasEmail($email);

}
