<?php

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerCustomerTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;
use Generated\Shared\Transfer\CustomerCustomer as CustomerTransferTransfer;
use Generated\Shared\Transfer\CustomerAddress as AddressTransferTransfer;

class SdkController extends AbstractSdkController
{
    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function registerAction(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->registerCustomer($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function confirmRegistrationAction(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->confirmRegistration($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function forgotPasswordAction(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->forgotPassword($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function restorePasswordAction(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->restorePassword($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     */
    public function deleteAction(CustomerCustomerTransfer $customerTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->deleteCustomer($customerTransfer);
        $this->setSuccess($success);

        return;
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function customerAction(CustomerCustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->getCustomer($customerTransfer);
    }

    /**
     * @param CustomerCustomerTransfer $customerTransfer
     *
     * @return CustomerCustomerTransfer
     */
    public function updateAction(CustomerCustomerTransfer $customerTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->updateCustomer($customerTransfer);
        $this->setSuccess($success);

        return $customerTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function addressAction(CustomerAddressTransfer $addressTransfer)
    {
        $addressTransfer = $this->getLocator()->customer()->facade()->getAddress($addressTransfer);
        if (!$addressTransfer) {
            $this->setSuccess(false);

            return null;
        }

        return $addressTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function updateAddressAction(CustomerAddressTransfer $addressTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->updateAddress($addressTransfer);
        $this->setSuccess($success);

        return $addressTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return CustomerAddressTransfer
     */
    public function newAddressAction(CustomerAddressTransfer $addressTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->newAddress($addressTransfer);
        $this->setSuccess($success);

        return $addressTransfer;
    }
}
