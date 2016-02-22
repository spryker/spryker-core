<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade;

use Spryker\Zed\Customer\Business\CustomerFacade;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CustomerCheckoutConnectorToCustomerBridge implements CustomerCheckoutConnectorToCustomerInterface
{

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacade
     */
    protected $customerFacade;

    /**
     * CustomerCheckoutConnectorToCustomerBridge constructor.
     *
     * @param \Spryker\Zed\Customer\Business\CustomerFacade $customerFacade
     */
    public function __construct($customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->customerFacade->getCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function updateCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->customerFacade->updateCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->customerFacade->registerCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function createAddress(AddressTransfer $addressTransfer)
    {
        return $this->customerFacade->createAddress($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function updateAddress(AddressTransfer $addressTransfer)
    {
        return $this->customerFacade->updateAddress($addressTransfer);
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function hasEmail($email)
    {
        return $this->customerFacade->hasEmail($email);
    }

}
