<?php

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;
use SprykerFeature\Shared\Customer\Transfer\Customer as CustomerTransfer;
use SprykerFeature\Shared\Customer\Transfer\Address as AddressTransfer;
use Generated\Zed\Ide\AutoCompletion;

class SdkController extends AbstractSdkController
{
    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function registerAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->registerCustomer($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function confirmRegistrationAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->confirmRegistration($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function forgotPasswordAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->forgotPassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function restorePasswordAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->restorePassword($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     */
    public function deleteAction(CustomerTransfer $customerTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->deleteCustomer($customerTransfer);
        $this->setSuccess($success);
        return;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function customerAction(CustomerTransfer $customerTransfer)
    {
        return $this->getLocator()->customer()->facade()->getCustomer($customerTransfer);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function updateAction(CustomerTransfer $customerTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->updateCustomer($customerTransfer);
        $this->setSuccess($success);
        return $customerTransfer;
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function addressAction(AddressTransfer $addressTransfer)
    {
        $addressTransfer = $this->getLocator()->customer()->facade()->getAddress($addressTransfer);
        if (!$addressTransfer) {
            $this->setSuccess(false);
            return null;
        }
        return $addressTransfer;
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function updateAddressAction(AddressTransfer $addressTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->updateAddress($addressTransfer);
        $this->setSuccess($success);
        return $addressTransfer;
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     */
    public function newAddressAction(AddressTransfer $addressTransfer)
    {
        $success = $this->getLocator()->customer()->facade()->newAddress($addressTransfer);
        $this->setSuccess($success);
        return $addressTransfer;
    }
}
