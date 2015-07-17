<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Customer\Service\Session;

use Generated\Shared\Customer\CustomerInterface;

interface CustomerSessionInterface
{

    /**
     * @param CustomerInterface $customerTransfer
     */
    public function logout(CustomerInterface $customerTransfer);

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function get(CustomerInterface $customerTransfer);

}
