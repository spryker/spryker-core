<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Client\Customer\Service\Session;

use Generated\Shared\Customer\CustomerInterface;

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
     * @return CustomerInterface
     */
    public function getCustomer();

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function setCustomer(CustomerInterface $customerTransfer);

}
