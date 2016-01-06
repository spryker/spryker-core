<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
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
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function getCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->customerFacade->getCustomer($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function updateCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->customerFacade->updateCustomer($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    public function registerCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->customerFacade->registerCustomer($customerTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function createAddress(AddressTransfer $addressTransfer)
    {
        return $this->customerFacade->createAddress($addressTransfer);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
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
