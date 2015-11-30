<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Customer\Service\Session;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerSessionInterface
{

    /**
     * @return mixed
     */
    public function logout();

    /**
     * @return bool
     */
    public function hasCustomer();

    /**
     * @return CustomerTransfer
     */
    public function getCustomer();

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function setCustomer(CustomerTransfer $customerTransfer);

}
