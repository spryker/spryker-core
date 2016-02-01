<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Client\Customer\Session;

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
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomer();

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomer(CustomerTransfer $customerTransfer);

}
